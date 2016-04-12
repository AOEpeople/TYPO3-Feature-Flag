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
 * @subpackage System_Typo3_Task
 * @author Kevin Schu <kevin.schu@aoe.com>
 * @author Roland Beisel <roland.beisel@aoe.com>
 */
class Tx_FeatureFlag_System_Typo3_Task_FlagEntries extends \TYPO3\CMS\Scheduler\Task\AbstractTask
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
     * @return Tx_FeatureFlag_Service
     */
    protected function getFeatureFlagService()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class)
            ->get(Tx_FeatureFlag_Service::class);
    }
}
