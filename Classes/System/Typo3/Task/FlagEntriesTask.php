<?php
namespace Aoe\FeatureFlag\System\Typo3\Task;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class FlagEntriesTask extends AbstractTask
{
    /**
     * @return bool
     */
    public function execute()
    {
        $this->getFeatureFlagService()->flagEntries();
        return true;
    }

    /**
     * @return FeatureFlagService
     */
    protected function getFeatureFlagService()
    {
        return GeneralUtility::makeInstance(ObjectManager::class)
            ->get(FeatureFlagService::class);
    }
}
