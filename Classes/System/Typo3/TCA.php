<?php

namespace Aoe\FeatureFlag\System\Typo3;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 AOE GmbH <dev@aoe.com>
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

use Aoe\FeatureFlag\Domain\Model\FeatureFlag;
use Aoe\FeatureFlag\Domain\Model\Mapping;
use Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository;
use Aoe\FeatureFlag\Domain\Repository\MappingRepository;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class TCA
{
    /**
     * @var string
     */
    public const FIELD_BEHAVIOR = 'tx_featureflag_behavior';

    /**
     * @var string
     */
    public const FIELD_FLAG = 'tx_featureflag_flag';

    protected static ?array $hashedMappings = null;

    /**
     * Hook for updates in Typo3 backend
     * @codingStandardsIgnoreStart
     */
    public function processDatamap_preProcessFieldArray(
        array &$incomingFieldArray,
        string $table,
        string $id,
        DataHandler $dataHandler
    ): void {
        // @codingStandardsIgnoreEnd
        if (
            array_key_exists(self::FIELD_BEHAVIOR, $incomingFieldArray) &&
            array_key_exists(self::FIELD_FLAG, $incomingFieldArray)
        ) {
            $pid = $dataHandler->getPID($table, (int) $id);
            $this->updateMapping(
                $table,
                (int) $id,
                $incomingFieldArray[self::FIELD_FLAG],
                (int) $pid,
                $incomingFieldArray[self::FIELD_BEHAVIOR]
            );
            unset($incomingFieldArray[self::FIELD_BEHAVIOR]);
            unset($incomingFieldArray[self::FIELD_FLAG]);
        }
    }

    /**
     * Hook for deletes in Typo3 Backend. It also delete all overwrite protection
     * @codingStandardsIgnoreStart
     */
    public function processCmdmap_postProcess(string $command, string $table, int $id): void
    {
        // @codingStandardsIgnoreEnd
        if ($command !== 'delete') {
            return;
        }

        $mappings = $this->getMappingRepository()
            ->findAllByForeignTableNameAndUid($id, $table);
        if (!is_array($mappings) && !$mappings instanceof QueryResultInterface) {
            return;
        }

        foreach ($mappings as $mapping) {
            if ($mapping instanceof Mapping) {
                $this->getMappingRepository()
                    ->remove($mapping);
            }
        }

        $this->getPersistenceManager()
            ->persistAll();
    }

    public function postOverlayPriorityLookup(string $table, array $row, array $status, string $iconName): string
    {
        if (!empty($row['uid']) && $this->isMappingAvailableForTableAndUid($row['uid'], $table)) {
            $mapping = $this->getMappingRepository()
                ->findOneByForeignTableNameAndUid($row['uid'], $table);
            if ($mapping instanceof Mapping) {
                if ($row['hidden'] === '1') {
                    return 'record-has-feature-flag-which-is-hidden';
                }

                if ($iconName !== '') {
                    // if record is e.g. hidden or protected by FE-group, than show that (e.g. 'hidden' or 'fe_group'-)overlay as default
                    return $iconName;
                }

                return 'record-has-feature-flag-which-is-visible';
            }
        }

        // return given icon-name as fall-back
        return $iconName;
    }

    /**
     * @todo fix code style
     */
    protected function updateMapping(string $table, int $id, int $featureFlag, int $pid, string $behavior): void
    {
        $mapping = $this->getMappingRepository()
            ->findOneByForeignTableNameAndUid($id, $table);

        if ($mapping instanceof Mapping) {
            if ($featureFlag === 0) {
                $this->getMappingRepository()
                    ->remove($mapping);
            } else {
                $mapping->setFeatureFlag($this->getFeatureFlagByUid($featureFlag));
                $mapping->setBehavior($behavior);
            }

            $mapping->setTstamp((string) time());
            $this->getMappingRepository()
                ->update($mapping);
        } elseif ($featureFlag !== 0) {
            /** @var Mapping $mapping */
            $mapping = new Mapping();
            $mapping->setPid($pid);
            $mapping->setFeatureFlag($this->getFeatureFlagByUid($featureFlag));
            $mapping->setForeignTableName($table);
            $mapping->setForeignTableUid($id);
            $mapping->setCrdate((string) time());
            $mapping->setTstamp((string) time());
            $mapping->setBehavior($behavior);
            $this->getMappingRepository()
                ->add($mapping);
        }

        $this->getPersistenceManager()
            ->persistAll();
    }

    protected function isMappingAvailableForTableAndUid(string $foreignTableUid, string $foreignTableName): bool
    {
        if (self::$hashedMappings === null) {
            self::$hashedMappings = $this->getMappingRepository()->getHashedMappings();
        }

        $identifier = sha1($foreignTableUid . '_' . $foreignTableName);

        return array_key_exists($identifier, self::$hashedMappings);
    }

    protected function getFeatureFlagByUid(int $uid): FeatureFlag
    {
        /** @var FeatureFlag $featureFlag */
        $featureFlag = $this->getFeatureFlagRepository()
            ->findByUid($uid);

        return $featureFlag;
    }

    protected function getMappingRepository(): object
    {
        return GeneralUtility::makeInstance(MappingRepository::class);
    }

    protected function getFeatureFlagRepository(): object
    {
        return GeneralUtility::makeInstance(FeatureFlagRepository::class);
    }

    protected function getPersistenceManager(): object
    {
        return GeneralUtility::makeInstance(PersistenceManager::class);
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
