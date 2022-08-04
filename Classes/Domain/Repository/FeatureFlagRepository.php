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

use Aoe\FeatureFlag\Service\FeatureFlagService;
use Aoe\FeatureFlag\System\Db\FeatureFlagData;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

class FeatureFlagRepository extends Repository
{
    private FeatureFlagData $featureFlagData;

    public function __construct(FeatureFlagData $featureFlagData)
    {
        $this->featureFlagData = $featureFlagData;
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        parent::__construct($objectManager);
    }


    public function initializeObject(): void
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $defaultQuerySettings */
        $defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $defaultQuerySettings->setRespectStoragePage(false);
        $defaultQuerySettings->setRespectSysLanguage(false);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    public function findByFlag(string $flag): ?object
    {
        $query = $this->createQuery();
        $query->getQuerySettings()
            ->setRespectSysLanguage(false);
        $query->getQuerySettings()
            ->setRespectStoragePage(false);
        $query->matching($query->equals('flag', $flag));

        return $query->execute()
            ->getFirst();
    }

    public function updateFeatureFlagStatusForTable(string $table): void
    {
        $this->hideEntries(
            $table,
            $this->getUpdateEntriesUids($table, FeatureFlagService::BEHAVIOR_HIDE, 1)
        );
        $this->hideEntries(
            $table,
            $this->getUpdateEntriesUids($table, FeatureFlagService::BEHAVIOR_SHOW, 0)
        );
        $this->showEntries(
            $table,
            $this->getUpdateEntriesUids($table, FeatureFlagService::BEHAVIOR_SHOW, 1)
        );
        $this->showEntries(
            $table,
            $this->getUpdateEntriesUids($table, FeatureFlagService::BEHAVIOR_HIDE, 0)
        );
    }

    private function hideEntries(string $table, array $uids): void
    {
        if (!empty($uids)) {
            $this->featureFlagData->updateContentElements($table, $uids, false);
        }
    }

    private function showEntries(string $table, array $uids): void
    {
        if (!empty($uids)) {
            $this->featureFlagData->updateContentElements($table, $uids, true);
        }
    }

    private function getUpdateEntriesUids(string $table, int $behavior, int $enabled): array
    {
        $rows = $this->featureFlagData->getContentElements($table, $behavior, $enabled);

        if (empty($rows)) {
            return $rows;
        }

        $uids = [];
        foreach ($rows as $row) {
            $uids[] = $row['uid'];
        }

        return $uids;
    }
}
