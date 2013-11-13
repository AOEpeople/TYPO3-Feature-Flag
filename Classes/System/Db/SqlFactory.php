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
 * @subpackage System_Db
 * @author Kevin Schu <kevin.schu@aoemedia.de>
 * @author Matthias Gutjahr <matthias.gutjahr@aoemedia.de>
 */
class Tx_FeatureFlag_System_Db_SqlFactory
{
    /**
     * @var string
     */
    const TABLE_MAPPING = 'tx_featureflag_domain_model_mapping';

    /**
     * @var string
     */
    const TABLE_FLAGS = 'tx_featureflag_domain_model_featureflag';

    /**
     * @param string $table
     * @param string $column
     * @param int $enabled
     * @return string
     */
    public function getSelectStatementForContentElements($table, $column, $enabled)
    {
        $statement = "SELECT $table.uid FROM ";
        $statement .= "$table,";
        $statement .= self::TABLE_MAPPING . ",";
        $statement .= self::TABLE_FLAGS;
        $statement .= " WHERE ";
        $statement .= self::TABLE_MAPPING . ".feature_flag = " . self::TABLE_FLAGS . ".uid";
        $statement .= " AND ";
        $statement .= "$table.uid = " . self::TABLE_MAPPING . ".foreign_table_uid";
        $statement .= " AND ";
        $statement .= self::TABLE_FLAGS . ".enabled = $enabled";
        $statement .= " AND ";
        $statement .= self::TABLE_MAPPING . ".foreign_table_name = '$table'";
        $statement .= " AND ";
        $statement .= self::TABLE_MAPPING . ".foreign_table_column = '$column'";
        $statement .= " AND ";
        $statement .= self::TABLE_FLAGS . ".deleted = 0";
        $statement .= " AND ";
        $statement .= self::TABLE_FLAGS . ".hidden = 0";
        return $statement;
    }

    /**
     * @param string $table
     * @return string
     */
    public function getUpdateStatementForContentElements($table)
    {
        return "UPDATE $table SET hidden = ? WHERE uid IN ?;";
    }

    /**
     * @return string
     */
    public function getUpdateStatementForMappingsAfterDeletion()
    {
        return "UPDATE " . self::TABLE_MAPPING . " SET processed = 1 WHERE deleted = 1;";
    }
}