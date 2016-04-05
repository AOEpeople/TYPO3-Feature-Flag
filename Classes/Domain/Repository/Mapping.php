<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 AOE GmbH <dev@aoe.com>
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
 * @subpackage Domain_Repository
 * @author Kevin Schu <kevin.schu@aoe.com>
 * @author Matthias Gutjahr <dev@aoe.com>
 */
class Tx_FeatureFlag_Domain_Repository_Mapping extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var Tx_FeatureFlag_System_Typo3_Configuration
     */
    private $configuration;

    /**
     * @var Tx_FeatureFlag_System_Db_SqlFactory
     */
    private $sqlFactory;

    /**
     *
     */
    public function __construct()
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        parent::__construct($objectManager);

        $this->configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_FeatureFlag_System_Typo3_Configuration');
        $this->sqlFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_FeatureFlag_System_Db_SqlFactory');
    }

    /**
     * @return void
     */
    public function initializeObject()
    {
        /** @var $defaultQuerySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $defaultQuerySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $defaultQuerySettings->setRespectStoragePage(false);
        $defaultQuerySettings->setRespectSysLanguage(false);
        $defaultQuerySettings->setIgnoreEnableFields(false)->setIncludeDeleted(false);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * @param $foreignTableUid
     * @param $foreignTableName
     * @return Tx_FeatureFlag_Domain_Model_Mapping
     */
    public function findOneByForeignTableNameAndUid($foreignTableUid, $foreignTableName)
    {
        return $this->findAllByForeignTableNameAndUid($foreignTableUid, $foreignTableName)->getFirst();
    }

    /**
     * @param $foreignTableUid
     * @param $foreignTableName
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllByForeignTableNameAndUid($foreignTableUid, $foreignTableName)
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd($query->equals('foreign_table_uid', $foreignTableUid),
            $query->equals('foreign_table_name', $foreignTableName)));
        return $query->execute();
    }

    /**
     * @param $featureFlagId
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllByFeatureFlag($featureFlagId)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('feature_flag', $featureFlagId));
        return $query->execute();
    }

    /**
     * @param $foreignTableName
     * @param $foreignTableUid
     * @return array
     */
    public function findContentElementPidsByForeignTableNameAndUid($foreignTableName, $foreignTableUid)
    {
        $pids = array();
        $statement = $this->sqlFactory->getSelectStatementForContentElementPids($foreignTableName, $foreignTableUid);

        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $query->statement($statement);

        $rows = $query->execute(true);
        foreach ($rows as $row) {
            $pids[] = $row['pid'];
        }

        return $pids;
    }

    /**
     * Get all mapping pIDs by given feature flag
     *
     * @param $featureFlagId
     * @return array
     */
    public function findAllContentElementPidsByFeatureFlag($featureFlagId)
    {
        $pids = array();

        $mappings = $this->findAllByFeatureFlag($featureFlagId);
        foreach ($mappings as $mapping) {
            if ($mapping instanceof Tx_FeatureFlag_Domain_Model_Mapping) {
                $pids = array_merge($pids, $this->findContentElementPidsByForeignTableNameAndUid(
                    $mapping->getForeignTableName(),
                    $mapping->getForeignTableUid()
                ));
            }
        }

        return array_unique($pids);
    }

    /**
     * @return array
     */
    public function getHashedMappings()
    {
        $mappings = $this->createQuery()->execute(true);
        $prepared = array();
        foreach ($mappings as $mapping) {
            $identifier = sha1($mapping['foreign_table_uid'] . '_' . $mapping['foreign_table_name']);
            $prepared[$identifier] = $identifier;
        }
        return $prepared;
    }
}
