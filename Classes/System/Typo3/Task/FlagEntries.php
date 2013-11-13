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
 * @subpackage System_Typo3_Task
 * @author Kevin Schu <kevin.schu@aoemedia.de>
 * @author Matthias Gutjahr <matthias.gutjahr@aoemedia.de>
 */
class Tx_FeatureFlag_System_Typo3_Task_FlagEntries extends tx_scheduler_Task
{
    /**
     * @return boolean
     */
    public function execute()
    {
        foreach ($this->getConfiguration()->getTables() as $table) {
            $this->getFeatureFlagRepository()->updateFeatureFlagStatusForTable($table);
        }
        return true;
    }

    /**
     * @return Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    protected function getFeatureFlagRepository()
    {
        return $this->getObjectManager()->get('Tx_FeatureFlag_Domain_Repository_FeatureFlag');
    }

    /**
     * @return Tx_FeatureFlag_System_Typo3_Configuration
     */
    protected function getConfiguration()
    {
        return $this->getObjectManager()->get('Tx_FeatureFlag_System_Typo3_Configuration');
    }

    /**
     * @return Tx_Extbase_Object_ObjectManager
     */
    private function getObjectManager()
    {
        return t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
    }
}