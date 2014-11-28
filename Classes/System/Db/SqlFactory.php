<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 AOE GmbH <dev@aoe.com>
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
     * @param integer $behavior Hide or show record?
     * @param integer $enabled Is feature flag enabled?
     * @return string
     */
    public function getSelectStatementForContentElements($table, $behavior, $enabled)
    {
        $escaptedTable = mysqli_real_escape_string($GLOBALS['TYPO3_DB']->getDatabaseHandle(), $table);

        $sql  = 'SELECT ' . $escaptedTable . '.uid';
        $sql .= ' FROM ' . $escaptedTable . ',' . self::TABLE_MAPPING . ',' . self::TABLE_FLAGS;
        $sql .= ' WHERE ' . self::TABLE_MAPPING . '.feature_flag=' . self::TABLE_FLAGS . '.uid';
        $sql .= ' AND ' . $escaptedTable . '.uid=' . self::TABLE_MAPPING . '.foreign_table_uid';
        $sql .= ' AND ' . self::TABLE_FLAGS . '.enabled=' . intval($enabled);
        $sql .= ' AND ' . self::TABLE_FLAGS . '.deleted=0';
        $sql .= ' AND ' . self::TABLE_FLAGS . '.hidden=0';
        $sql .= ' AND ' . self::TABLE_MAPPING . '.foreign_table_name="' . $escaptedTable . '"';
        $sql .= ' AND ' . self::TABLE_MAPPING . '.behavior=' . intval($behavior);

        return $sql;
    }

    /**
     * @param string $table
     * @return string
     */
    public function getUpdateStatementForContentElements($table)
    {
        return "UPDATE " . mysqli_real_escape_string($GLOBALS['TYPO3_DB']->getDatabaseHandle(), $table) . " SET hidden = ? WHERE uid IN ?;";
    }
}
