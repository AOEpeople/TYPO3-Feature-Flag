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
use Aoe\FeatureFlag\Service\Exception\FeatureNotFoundException;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class TCA
{
    /**
     * @var string
     */
    const FIELD_BEHAVIOR = 'tx_featureflag_behavior';

    /**
     * @var string
     */
    const FIELD_FLAG = 'tx_featureflag_flag';

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var QueryResultInterface
     */
    protected static $hashedMappings;

    /**
     * Hook for updates in Typo3 backend
     * @param array $incomingFieldArray
     * @param string $table
     * @param integer $id
     * @param DataHandler $dataHandler
     * @codingStandardsIgnoreStart
     */
    public function processDatamap_preProcessFieldArray(
        &$incomingFieldArray,
        $table,
        $id,
        DataHandler $dataHandler
    ) {
        // @codingStandardsIgnoreEnd
        if (
            array_key_exists(self::FIELD_BEHAVIOR, $incomingFieldArray) &&
            array_key_exists(self::FIELD_FLAG, $incomingFieldArray)
        ) {
            $pid = $dataHandler->getPID($table, $id);
            $this->updateMapping($table, $id, $incomingFieldArray[self::FIELD_FLAG], $pid, $incomingFieldArray[self::FIELD_BEHAVIOR]);
            unset($incomingFieldArray[self::FIELD_BEHAVIOR]);
            unset($incomingFieldArray[self::FIELD_FLAG]);
        }
    }

    /**
     * Hook for deletes in Typo3 Backend. It also delete all overwrite protection
     * @param string $command
     * @param string $table
     * @param integer $id
     * @codingStandardsIgnoreStart
     */
    public function processCmdmap_postProcess($command, $table, $id)
    {
        // @codingStandardsIgnoreEnd
        if ($command !== 'delete') {
            return;
        }
        $mappings = $this->getMappingRepository()->findAllByForeignTableNameAndUid($id, $table);
        if (false === is_array($mappings) && false === ($mappings instanceof QueryResultInterface)) {
            return;
        }
        foreach ($mappings as $mapping) {
            if ($mapping instanceof Mapping) {
                $this->getMappingRepository()->remove($mapping);
            }
        }
        $this->getPersistenceManager()->persistAll();
    }

    /**
     * @param string $table
     * @param array $row
     * @param string $status
     * @param string $iconName
     * @return string
     */
    public function postOverlayPriorityLookup($table, $row, &$status, $iconName)
    {
        if ($this->isMappingAvailableForTableAndUid($row['uid'], $table)) {
            $mapping = $this->getMappingRepository()->findOneByForeignTableNameAndUid($row['uid'], $table);
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
     * @param string $table
     * @param int $id
     * @param int $featureFlag
     * @param int $pid
     * @param string $behavior
     */
    protected function updateMapping($table, $id, $featureFlag, $pid, $behavior)
    {
        $mapping = $this->getMappingRepository()->findOneByForeignTableNameAndUid($id, $table);
        if ($mapping instanceof Mapping) {
            if ('0' === $featureFlag) {
                $this->getMappingRepository()->remove($mapping);
            } else {
                $mapping->setFeatureFlag($this->getFeatureFlagByUid($featureFlag));
                $mapping->setBehavior($behavior);
            }
            $mapping->setTstamp(time());
            $this->getMappingRepository()->update($mapping);
        } elseif ('0' !== $featureFlag) {
            /** @var Mapping $mapping */
            $mapping = $this->getObjectManager()->get(Mapping::class);
            $mapping->setPid($pid);
            $mapping->setFeatureFlag($this->getFeatureFlagByUid($featureFlag));
            $mapping->setForeignTableName($table);
            $mapping->setForeignTableUid($id);
            $mapping->setCrdate(time());
            $mapping->setTstamp(time());
            $mapping->setBehavior($behavior);
            $this->getMappingRepository()->add($mapping);
        }
        $this->getPersistenceManager()->persistAll();
    }

    /**
     * @param int $foreignTableUid
     * @param string $foreignTableName
     * @return bool
     */
    protected function isMappingAvailableForTableAndUid($foreignTableUid, $foreignTableName)
    {
        if (null === self::$hashedMappings) {
            self::$hashedMappings = $this->getMappingRepository()->getHashedMappings();
        }
        $identifier = sha1($foreignTableUid . '_' . $foreignTableName);
        if (array_key_exists($identifier, self::$hashedMappings)) {
            return true;
        }

        return false;
    }

    /**
     * @param int $uid
     * @return FeatureFlag
     * @throws FeatureNotFoundException
     */
    protected function getFeatureFlagByUid($uid)
    {
        /** @var FeatureFlag $featureFlag */
        $featureFlag = $this->getFeatureFlagRepository()->findByUid($uid);
        if (false === ($featureFlag instanceof FeatureFlag)) {
            throw new FeatureNotFoundException(
                'Feature Flag not found by uid: "' . $uid . '"',
                1384340431
            );
        }

        return $featureFlag;
    }

    /**
     * @return MappingRepository
     */
    protected function getMappingRepository()
    {
        return $this->getObjectManager()->get(MappingRepository::class);
    }

    /**
     * @return FeatureFlagRepository
     */
    protected function getFeatureFlagRepository()
    {
        return $this->getObjectManager()->get(FeatureFlagRepository::class);
    }

    /**
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        if (false === ($this->objectManager instanceof ObjectManager)) {
            $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        }

        return $this->objectManager;
    }

    /**
     * @return PersistenceManager
     */
    protected function getPersistenceManager()
    {
        if (false === $this->persistenceManager instanceof PersistenceManager) {
            $this->persistenceManager = $this->getObjectManager()->get(PersistenceManager::class);
        }

        return $this->persistenceManager;
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
