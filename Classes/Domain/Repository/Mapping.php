<?php

class Tx_FeatureFlag_Domain_Repository_Mapping extends Tx_Extbase_Persistence_Repository
{
    /**
     * @var Tx_FeatureFlag_System_Db_SqlFactory
     */
    private $sqlFactory;

    /**
     * @param Tx_FeatureFlag_System_Db_SqlFactory $sqlFactory
     */
    public function injectSqlFactory(Tx_FeatureFlag_System_Db_SqlFactory $sqlFactory)
    {
        $this->sqlFactory = $sqlFactory;
    }

    /**
     * @return void
     */
    public function initializeObject()
    {
        /** @var $defaultQuerySettings Tx_Extbase_Persistence_Typo3QuerySettings */
        $defaultQuerySettings = $this->objectManager->get('Tx_Extbase_Persistence_Typo3QuerySettings');
        $defaultQuerySettings->setRespectStoragePage(FALSE);
        $defaultQuerySettings->setRespectEnableFields(TRUE);
        $defaultQuerySettings->setRespectSysLanguage(FALSE);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * @param $foreignTableUid
     * @param $foreignTableName
     * @param $foreignTableColumn
     * @return null|Tx_FeatureFlag_Domain_Model_Mapping
     */
    public function findByForeignTableNameUidAndColumnName($foreignTableUid, $foreignTableName, $foreignTableColumn)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('foreign_table_uid', $foreignTableUid),
                $query->equals('foreign_table_name', $foreignTableName),
                $query->equals('foreign_table_column', $foreignTableColumn)
            )
        );
        return $query->execute()->getFirst();
    }

    /**
     * @param $foreignTableUid
     * @param $foreignTableName
     * @return array|Tx_Extbase_Persistence_QueryResultInterface
     */
    public function findByForeignTableNameAndUid($foreignTableUid, $foreignTableName)
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
}