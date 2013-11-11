<?php

class Tx_FeatureFlag_Domain_Repository_FeatureFlag extends Tx_Extbase_Persistence_Repository
{
    /**
     * @return void
     */
    public function initializeObject()
    {
        /** @var $defaultQuerySettings Tx_Extbase_Persistence_Typo3QuerySettings */
        $defaultQuerySettings = $this->objectManager->get('Tx_Extbase_Persistence_Typo3QuerySettings');
        $defaultQuerySettings->setRespectStoragePage(FALSE);
        $defaultQuerySettings->setRespectEnableFields(FALSE);
        $defaultQuerySettings->setRespectSysLanguage(FALSE);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * @param string $flag
     * @return object
     */
    public function findByFlag($flag)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(FALSE);
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->getQuerySettings()->setRespectEnableFields(FALSE);
        $query->matching($query->equals('flag', $flag));
        return $query->execute()->getFirst();
    }

    /**
     * @param string $table
     */
    public function updateFeatureFlagStatusForTable($table)
    {
        $this->hideEntries($table, $this->getUpdateEntriesUids($table));
        $this->unHideEntries($table, $this->getUpdateEntriesUids($table, 1));
    }

    /**
     * @param string $table
     * @param array $uids
     * @return array|Tx_Extbase_Persistence_QueryResultInterface
     */
    private function hideEntries($table, array $uids)
    {
        return $this->updateEntries($table, $uids, 1);
    }

    /**
     * @param string $table
     * @param array $uids
     * @return array|Tx_Extbase_Persistence_QueryResultInterface
     */
    private function unHideEntries($table, array $uids)
    {
        return $this->updateEntries($table, $uids, 0);
    }

    /**
     * @param $table
     * @param array $uids
     * @param $hidden
     * @return array|Tx_Extbase_Persistence_QueryResultInterface
     */
    private function updateEntries($table, array $uids, $hidden)
    {
        if (empty($uids)) {
            return array();
        }
        $statement = "UPDATE $table SET hidden = ? WHERE uid IN (?);";
        /** @var Tx_Extbase_Persistence_Query $query */
        $query = $this->createQuery();
        $query->statement($statement, array($hidden, $uids));
        $query->getQuerySettings()->setRespectEnableFields(FALSE);
        $query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        return $query->execute();
    }

    /**
     * @param string $table
     * @param int $enabled
     * @return array
     */
    private function getUpdateEntriesUids($table, $enabled = 0)
    {
        $statement = "SELECT $table.uid FROM ";
        $statement .= "$table,";
        $statement .= "tx_featureflag_table_featureflag_mm,";
        $statement .= "tx_featureflag_domain_model_featureflag";
        $statement .= " WHERE ";
        $statement .= "tx_featureflag_table_featureflag_mm.uid_foreign = tx_featureflag_domain_model_featureflag.uid";
        $statement .= " AND ";
        $statement .= "$table.uid = tx_featureflag_table_featureflag_mm.uid_local";
        $statement .= " AND ";
        $statement .= "tx_featureflag_domain_model_featureflag.enabled = $enabled;";
        /** @var Tx_Extbase_Persistence_Query $query */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectEnableFields(FALSE);
        $query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $query->statement($statement);
        $uids = array();
        foreach ($query->execute() as $row) {
            $uids[] = $row['uid'];
        }
        return $uids;
    }
}