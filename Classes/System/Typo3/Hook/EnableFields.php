<?php

/**
 * Class Tx_FeatureFlag_System_Typo3_Hook_EnableFields
 */
class Tx_FeatureFlag_System_Typo3_Hook_EnableFields
{
    /**
     * @var array
     */
    private $tables = array(
        'pages',
        //'tt_content'
    );

    /**
     * @var Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    private $featureFlagRepository;

    /**
     * Inject feature flag repository
     */
    public function __construct()
    {
        $objectManager = new Tx_Extbase_Object_ObjectManager();
        $this->featureFlagRepository = $objectManager->get('Tx_FeatureFlag_Domain_Repository_FeatureFlag');
    }

    /**
     * @param array $params
     * @param object $obj
     * @return string
     */
    public function enableFields($params, $obj)
    {
        //echo "<pre>";
        //var_dump($this->createWhereClauseForTable($params['table']));die();
        return $this->createWhereClauseForTable($params['table']);
    }

    /**
     * @param string $table
     * @return string
     */
    private function createWhereClauseForTable($table)
    {
        $where = '';
        if (FALSE === $this->isAllowedToExtend($table)) {
            return $where;
        }
        $where .= ' AND (' . $table . '.tx_featureflag_featureflag=""';
        foreach ($this->featureFlagRepository->findAll() as $featureFlag) {
            /** @var Tx_FeatureFlag_Domain_Model_FeatureFlag $featureFlag */
            if (true === $featureFlag->isEnabled()) {
                $where .= ' OR ' . $table . '.tx_featureflag_featureflag=' . (string)$featureFlag->getUid();
            }
        }
        $where .= ')';
        return $where;
    }

    /**
     * @param string $table
     * @return bool
     */
    private function isAllowedToExtend($table)
    {
        if (true === $this->isTableAllowedToExtend($table) && true === $this->isInFrontendContext()) {
            return true;
        }
        return false;
    }

    /**
     * @param string $table
     * @return bool
     */
    private function isTableAllowedToExtend($table)
    {
        if (in_array($table, $this->tables)) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isInFrontendContext()
    {
        if (TYPO3_MODE === 'BE') {
            return false;
        }
        return true;
    }
}