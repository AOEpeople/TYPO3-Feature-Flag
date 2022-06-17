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

use Aoe\FeatureFlag\Domain\Model\FeatureFlag;
use Aoe\FeatureFlag\Service\FeatureFlagService;
use Aoe\FeatureFlag\System\Db\FeatureFlagData;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

class FeatureFlagRepository extends Repository
{
    /**
     * @var FeatureFlagData
     */
    private $featureFlagData;

    public function __construct(FeatureFlagData $featureFlagData)
    {
        $this->featureFlagData = $featureFlagData;
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        parent::__construct($objectManager);
    }


    public function initializeObject()
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $defaultQuerySettings */
        $defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $defaultQuerySettings->setRespectStoragePage(false);
        $defaultQuerySettings->setRespectSysLanguage(false);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * @param string $flag
     * @return FeatureFlag
     */
    public function findByFlag($flag)
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

    /**
     * @param string $table
     */
    public function updateFeatureFlagStatusForTable($table)
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

    /**
     * @param string $table
     * @param array $uids
     */
    private function hideEntries($table, array $uids)
    {
        if (!empty($uids)) {
            $this->featureFlagData->updateContentElements($table, $uids, false);
        }
    }

    /**
     * @param string $table
     * @param array $uids
     */
    private function showEntries($table, array $uids)
    {
        if (!empty($uids)) {
            $this->featureFlagData->updateContentElements($table, $uids, true);
        }
    }

    /**
     * @param string $table
     * @param int $behavior
     * @param int $enabled
     * @return array
     */
    private function getUpdateEntriesUids($table, $behavior, $enabled)
    {
        $rows = $this->featureFlagData->getContentElements($table, $behavior, $enabled);

        if (empty($rows)) {
            return $rows;
        }

        foreach ($rows as $row) {
            $uids[] = $row['uid'];
        }

        return $uids;
    }
}
