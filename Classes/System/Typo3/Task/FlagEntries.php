<?php

/**
 * Class Tx_FeatureFlag_System_Typo3_Hook_EnableFields
 */
class Tx_FeatureFlag_System_Typo3_Task_FlagEntries extends tx_scheduler_Task
{
    /**
     * @return boolean
     */
    public function execute()
    {
        return TRUE;
    }

    /**
     * @return Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    private function getFeatureFlagRepository()
    {
        return $this->getObjectManager()->get('Tx_FeatureFlag_Domain_Repository_FeatureFlag');
    }

    /**
     * @return Tx_Extbase_Persistence_Manager
     */
    private function getPersistenceManager()
    {
        return $this->getObjectManager()->get('Tx_Extbase_Persistence_Manager');
    }

    /**
     * @return Tx_Extbase_Object_ObjectManager
     */
    private function getObjectManager()
    {
        return t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
    }
}