<?php

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
 * @subpackage Domain_Repository
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_FeatureFlag_Domain_Repository_FeatureFlag extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var Tx_FeatureFlag_System_Db_SqlFactory
     * @inject
     */
    private $sqlFactory;

    /**
     *
     */
    public function __construct()
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        parent::__construct($objectManager);
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
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * @param string $flag
     * @return Tx_FeatureFlag_Domain_Model_FeatureFlag
     */
    public function findByFlag($flag)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('flag', $flag));

        return $query->execute()->getFirst();
    }

    /**
     * @param string $table
     */
    public function updateFeatureFlagStatusForTable($table)
    {
        $this->hideEntries($table, $this->getUpdateEntriesUids($table, Tx_FeatureFlag_Service::BEHAVIOR_HIDE, 1));
        $this->hideEntries($table, $this->getUpdateEntriesUids($table, Tx_FeatureFlag_Service::BEHAVIOR_SHOW, 0));
        $this->showEntries($table, $this->getUpdateEntriesUids($table, Tx_FeatureFlag_Service::BEHAVIOR_SHOW, 1));
        $this->showEntries($table, $this->getUpdateEntriesUids($table, Tx_FeatureFlag_Service::BEHAVIOR_HIDE, 0));
    }

    /**
     * @param string $table
     * @param array $uids
     * @return void
     */
    private function hideEntries($table, array $uids)
    {
        $this->updateEntries($table, $uids, false);
    }

    /**
     * @param string $table
     * @param array $uids
     * @return void
     */
    private function showEntries($table, array $uids)
    {
        $this->updateEntries($table, $uids, true);
    }

    /**
     * @param $table
     * @param array $uids
     * @param boolean $isVisible
     * @return void
     */
    private function updateEntries($table, array $uids, $isVisible)
    {
        if (empty($uids)) {
            return;
        }
        $statement = $this->sqlFactory->getUpdateStatementForContentElements($table, $uids, $isVisible);
        $GLOBALS['TYPO3_DB']->sql_query($statement);
    }

    /**
     * @param string $table
     * @param int $behavior
     * @param int $enabled
     * @return array
     */
    private function getUpdateEntriesUids($table, $behavior, $enabled)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true)->setIncludeDeleted(true);
        $statement = $this->sqlFactory->getSelectStatementForContentElements($table, $behavior, $enabled);
        $query->statement($statement);
        $uids = array();
        $rows = $query->execute(true);
        foreach ($rows as $row) {
            $uids[] = $row['uid'];
        }

        return $uids;
    }
}
