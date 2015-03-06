<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 AOE GmbH <dev@aoe.com>
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
 */
class Tx_FeatureFlag_System_Typo3_Task_FlagEntries extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * @return boolean
     */
    public function execute()
    {
        /** @var Tx_FeatureFlag_Domain_Repository_FeatureFlag $repository */
        $repository = $this->getObjectManager()->get('Tx_FeatureFlag_Domain_Repository_FeatureFlag');
        /** @var Tx_FeatureFlag_System_Typo3_Configuration $configuration */
        $configuration = $this->getObjectManager()->get('Tx_FeatureFlag_System_Typo3_Configuration');
        foreach ($configuration->getTables() as $table) {
            $repository->updateFeatureFlagStatusForTable($table);
        }
        return true;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
     */
    protected function getObjectManager()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
    }
}
