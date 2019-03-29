<?php

namespace Aoe\FeatureFlag;

use Aoe\FeatureFlag\Domain\Repository\FeatureFlag;
use Aoe\FeatureFlag\Service\Exception\FeatureNotFound;
use Aoe\FeatureFlag\System\Typo3\Configuration;
use RuntimeException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\EndTimeRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\RootLevelRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\StartTimeRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package FeatureFlag
 */
class Service
{
    /**
     * @var int
     */
    const BEHAVIOR_HIDE = 0;

    /**
     * @var int
     */
    const BEHAVIOR_SHOW = 1;

    /**
     * @var FeatureFlag
     */
    private $featureFlagRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     */
    private $persistenceManager;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var array
     */
    private $cachedFlags = array();

    /**
     * @param FeatureFlag $featureFlagRepository
     * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
     * @param Configuration $configuration
     */
    public function __construct(
        FeatureFlag $featureFlagRepository,
        \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager,
        Configuration $configuration
    ) {
        $this->featureFlagRepository = $featureFlagRepository;
        $this->persistenceManager = $persistenceManager;
        $this->configuration = $configuration;
    }

    /**
     * @param string $flag
     * @return \Aoe\FeatureFlag\Domain\Model\FeatureFlag
     * @throws FeatureNotFound
     * @throws RuntimeException
     * @return boolean
     */
    protected function getFeatureFlag($flag)
    {
        if (false === is_array($GLOBALS['TCA']) || false === isset($GLOBALS['TCA']['tx_featureflag_domain_model_featureflag'])) {
            // This can happen, when we call a REST-endpoint (by using restler-extension without initialized FE) and TYPO3 7:
            // Without TCA, we would load (in this and ALL other following PHP-requests) the featureFlag without initialized
            // 'property-values' (method 'FeatureFlag::isEnabled' would return NULL), so we MUST
            // avoid to load any featureFlag without correct loaded TCA!
            throw new RuntimeException('TCA is not loaded - we can\'t load featureFlag "'.$flag.'"');
        }

        if (false === array_key_exists($flag, $this->cachedFlags)) {
            $flagModel = $this->featureFlagRepository->findByFlag($flag);
            if (false === $flagModel instanceof \Aoe\FeatureFlag\Domain\Model\FeatureFlag) {
                throw new FeatureNotFound('Feature Flag not found: "' . $flag . '"', 1383842028);
            }
            $this->cachedFlags[$flag] = $flagModel;
        }
        return $this->cachedFlags[$flag];
    }

    /**
     * @param $flag
     * @return bool
     * @throws FeatureNotFound
     */
    public function isFeatureEnabled($flag)
    {
        return $this->getFeatureFlag($flag)->isEnabled();
    }

    /**
     * @param $flag
     * @param $enabled
     * @throws FeatureNotFound
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function updateFeatureFlag($flag, $enabled)
    {
        $flagModel = $this->getFeatureFlag($flag);
        $flagModel->setEnabled($enabled);

        $this->featureFlagRepository->update($flagModel);
        $this->persistenceManager->persistAll();

        $this->cachedFlags[$flag] = $flagModel;
    }

    /**
     * Flags entries in database
     */
    public function flagEntries()
    {
        foreach ($this->configuration->getTables() as $table) {
            $this->updateFeatureFlagStatusForTable($table);
        }
    }

    private function updateFeatureFlagStatusForTable($table): void {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $elements = $this->getElementsWithFeatureFlagMapping($table, $queryBuilder);
        $elementsToHide = [];
        $elementsToShow = [];
        foreach($elements as $element){
            //hide element when ff active
            if ($element['behavior'] === 0 &&
                $element['enabled'] === 1 &&
                $element['hidden'] === 0){
                array_push($elementsToHide, $element['uid']);
            }

            //show element when ff active
            if ($element['behavior'] === 1 &&
                $element['enabled'] === 1 &&
                $element['hidden'] === 1){
                array_push($elementsToShow, $element['uid']);
            }

            //hide element when ff inactive
            if ($element['behavior'] === 1 &&
                $element['enabled'] === 0 &&
                $element['hidden'] === 0){
                array_push($elementsToHide, $element['uid']);
            }

            //show element when ff inactive
            if ($element['behavior'] === 0 &&
                $element['enabled'] === 0 &&
                $element['hidden'] === 1){
                array_push($elementsToShow, $element['uid']);
            }
        }

        $this->hideElements($table, $elementsToHide, $queryBuilder);
        $this->showElements($table, $elementsToShow, $queryBuilder);
    }

    /**
     * @param $table
     * @return array
     */
    private function getElementsWithFeatureFlagMapping($table,QueryBuilder $queryBuilder): array
    {
        $queryBuilder->getRestrictions()
            ->removeByType(HiddenRestriction::class)
            ->removeByType(StartTimeRestriction::class)
            ->removeByType(EndTimeRestriction::class)
            ->removeByType(RootLevelRestriction::class);
        $query = $queryBuilder
            ->select("$table.uid", "$table.hidden", "mapping.behavior", "featureflag.enabled")
            ->from($table)
            ->leftJoin(
                $table,
                'tx_featureflag_domain_model_mapping',
                'mapping',
                $queryBuilder->expr()->eq('mapping.foreign_table_uid', $queryBuilder->quoteIdentifier($table . '.uid'))
            )
            ->leftJoin(
                'mapping',
                'tx_featureflag_domain_model_featureflag',
                'featureflag',
                $queryBuilder->expr()->eq('mapping.feature_flag', $queryBuilder->quoteIdentifier('featureflag' . '.uid'))
            )
            ->where(
                $queryBuilder->expr()->eq('mapping.foreign_table_name', $queryBuilder->createNamedParameter($table))
            );
        $elements = $query->execute()->fetchAll();
        return $elements;
    }

    /**
     * @param $table
     * @param array $elementsToHide
     * @param QueryBuilder $queryBuilder
     */
    private function hideElements($table, array $elementsToHide, QueryBuilder $queryBuilder): void
    {
        if (count($elementsToHide) > 0) {
            $queryBuilder->update($table)
                ->where(
                    $queryBuilder->expr()->in('uid', $elementsToHide)
                )
                ->set('hidden', '1')
                ->execute();
        }
    }

    /**
     * @param $table
     * @param array $elementsToShow
     * @param QueryBuilder $queryBuilder
     */
    private function showElements($table, array $elementsToShow, QueryBuilder $queryBuilder): void
    {
        if (count($elementsToShow) > 0) {
            $queryBuilder->update($table)
                ->where(
                    $queryBuilder->expr()->in('uid', $elementsToShow)
                )
                ->set('hidden', '0')
                ->execute();
        }
    }
}