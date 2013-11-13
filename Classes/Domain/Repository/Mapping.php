<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 AOE GmbH <dev@aoemedia.de>
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
 * @author Kevin Schu <kevin.schu@aoemedia.de>
 * @author Matthias Gutjahr <matthias.gutjahr@aoemedia.de>
 */
class Tx_FeatureFlag_Domain_Repository_Mapping extends Tx_Extbase_Persistence_Repository
{
    /**
     * @var Tx_FeatureFlag_System_Db_SqlFactory
     */
    private $sqlFactory;

    /**
     * @param Tx_FeatureFlag_System_Db_SqlFactory $sqlFactory
     */
    public function injectSqlFactory(Tx_FeatureFlag_System_Db_SqlFactory $sqlFactory)
    {
        $this->sqlFactory = $sqlFactory;
    }

    /**
     * @return void
     */
    public function initializeObject()
    {
        /** @var $defaultQuerySettings Tx_Extbase_Persistence_Typo3QuerySettings */
        $defaultQuerySettings = $this->objectManager->get('Tx_Extbase_Persistence_Typo3QuerySettings');
        $defaultQuerySettings->setRespectStoragePage(FALSE);
        $defaultQuerySettings->setRespectEnableFields(TRUE);
        $defaultQuerySettings->setRespectSysLanguage(FALSE);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * @param $foreignTableUid
     * @param $foreignTableName
     * @param $foreignTableColumn
     * @return null|Tx_FeatureFlag_Domain_Model_Mapping
     */
    public function findByForeignTableNameUidAndColumnName($foreignTableUid, $foreignTableName, $foreignTableColumn)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('foreign_table_uid', $foreignTableUid),
                $query->equals('foreign_table_name', $foreignTableName),
                $query->equals('foreign_table_column', $foreignTableColumn)
            )
        );
        return $query->execute()->getFirst();
    }

    /**
     * @param $foreignTableUid
     * @param $foreignTableName
     * @return array|Tx_Extbase_Persistence_QueryResultInterface
     */
    public function findByForeignTableNameAndUid($foreignTableUid, $foreignTableName)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('foreign_table_uid', $foreignTableUid),
                $query->equals('foreign_table_name', $foreignTableName)
            )
        );
        return $query->execute();
    }
}