<?php

namespace Aoe\FeatureFlag\Domain\Repository;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MappingRepository extends Repository
{
    public function initializeObject(): void
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $defaultQuerySettings */
        $defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $defaultQuerySettings->setRespectStoragePage(false);
        $defaultQuerySettings->setRespectSysLanguage(false);
        $defaultQuerySettings->setIgnoreEnableFields(false)
            ->setIncludeDeleted(false);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    public function findOneByForeignTableNameAndUid(string $foreignTableUid, string $foreignTableName): ?object
    {
        return $this->findAllByForeignTableNameAndUid($foreignTableUid, $foreignTableName)
            ->getFirst();
    }

    /**
     * @return QueryResultInterface
     */
    public function findAllByForeignTableNameAndUid(string $foreignTableUid, string $foreignTableName)
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

    /**
     * @return array|QueryResultInterface
     */
    public function findAllByFeatureFlag(string $featureFlagId)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()
            ->setRespectSysLanguage(false);
        $query->getQuerySettings()
            ->setRespectStoragePage(false);
        $query->matching($query->equals('feature_flag', $featureFlagId));
        return $query->execute();
    }

    public function getHashedMappings(): array
    {
        $mappings = $this->createQuery()
            ->execute(true);
        $prepared = [];
        foreach ($mappings as $mapping) {
            $identifier = sha1($mapping['foreign_table_uid'] . '_' . $mapping['foreign_table_name']);
            $prepared[$identifier] = $identifier;
        }
        return $prepared;
    }
}
