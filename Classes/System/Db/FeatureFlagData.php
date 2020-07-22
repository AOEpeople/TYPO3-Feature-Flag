<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 AOE GmbH <dev@aoe.com>
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

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Tx_FeatureFlag_System_Db_FeatureFlagData
 */
class Tx_FeatureFlag_System_Db_FeatureFlagData
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
     * @param integer $behavior
     * @param integer $enabled
     *
     * @return array
     */
    public function getContentElements($table, $behavior, $enabled)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $queryBuilder->getRestrictions()->removeAll();

        $queryBuilder
            ->select($table . '.uid')
            ->from($table)
            ->from(self::TABLE_MAPPING)
            ->from(self::TABLE_FLAGS)
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq(
                        self::TABLE_MAPPING . '.feature_flag',
                        self::TABLE_FLAGS . '.uid'
                    ),
                    $queryBuilder->expr()->eq(
                        $table . '.uid',
                        self::TABLE_MAPPING . '.foreign_table_uid'
                    ),
                    $queryBuilder->expr()->eq(
                        self::TABLE_FLAGS . '.enabled',
                        $queryBuilder->createNamedParameter($enabled, Connection::PARAM_INT)
                    ),
                    $queryBuilder->expr()->eq(
                        self::TABLE_MAPPING . '.foreign_table_name',
                        $queryBuilder->createNamedParameter($table, Connection::PARAM_STR)
                    ),
                    $queryBuilder->expr()->eq(
                        self::TABLE_MAPPING . '.behavior',
                        $queryBuilder->createNamedParameter($behavior, Connection::PARAM_INT)
                    )
                )
            );

        return $queryBuilder->execute()->fetchAll();
    }

    /**
     * @param string $table
     * @param array $uids
     * @param bool $isVisible
     */
    public function updateContentElements($table, array $uids, $isVisible)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $queryBuilder->getRestrictions()->removeAll();

        $query = $queryBuilder
            ->update($table)
            ->set('hidden', $isVisible === true ? 0 : 1)
            ->add('where', $queryBuilder->expr()->in('uid', $uids));

        $query->execute();
    }

    /**
     * @param string $table
     * @param integer $uid
     *
     * @return string
     */
    public function getContentElementsPIDs($table, $uid)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $queryBuilder->getRestrictions()->removeAll();

        $query = $queryBuilder
            ->select($table . '.pid')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq(
                    $table . '.uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)
                )
            );

        return $query->execute()->fetchColumn(0);
    }
}
