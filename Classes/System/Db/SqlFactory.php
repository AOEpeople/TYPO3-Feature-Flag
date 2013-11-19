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
     * @param int $behavior Hide or show record?
     * @param int $enabled Is feature flag enabled?
     * @return string
     */
    public function getSelectStatementForContentElements($table, $behavior, $enabled)
    {
        $statement = 'SELECT ' . mysql_real_escape_string($table) . '.uid FROM ';
        $statement .= mysql_real_escape_string($table) . ',';
        $statement .= mysql_real_escape_string(self::TABLE_MAPPING) . ',';
        $statement .= mysql_real_escape_string(self::TABLE_FLAGS);
        $statement .= ' WHERE ';
        $statement .= mysql_real_escape_string(self::TABLE_MAPPING) . '.feature_flag = ' . mysql_real_escape_string(self::TABLE_FLAGS) . '.uid';
        $statement .= ' AND ';
        $statement .= mysql_real_escape_string($table) . '.uid = ' . mysql_real_escape_string(self::TABLE_MAPPING) . '.foreign_table_uid';
        $statement .= ' AND ';
        $statement .= mysql_real_escape_string(self::TABLE_FLAGS) . '.enabled = "' . mysql_real_escape_string($enabled) . '"';
        $statement .= ' AND ';
        $statement .= mysql_real_escape_string(self::TABLE_MAPPING) . '.foreign_table_name = "' . mysql_real_escape_string($table) . '"';
        $statement .= ' AND ';
        $statement .= mysql_real_escape_string(self::TABLE_MAPPING) . '.behavior = "' . mysql_real_escape_string($behavior) . '"';
        $statement .= ' AND ';
        $statement .= mysql_real_escape_string(self::TABLE_FLAGS) . '.deleted = 0';
        $statement .= ' AND ';
        $statement .= mysql_real_escape_string(self::TABLE_FLAGS) . '.hidden = 0';
        return $statement;
    }

    /**
     * @param string $table
     * @return string
     */
    public function getUpdateStatementForContentElements($table)
    {
        return "UPDATE " . mysql_real_escape_string($table) . " SET hidden = ? WHERE uid IN ?;";
    }
}