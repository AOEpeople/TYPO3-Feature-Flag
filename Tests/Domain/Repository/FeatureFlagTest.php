<?php

/**
 * Tx_FeatureFlag_Domain_Repository_FeatureFlagTest test case.
 */
class Tx_FeatureFlag_Domain_Repository_FeatureFlagTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    private $featureFlag;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $sqlFactory = $this->getMock('Tx_FeatureFlag_System_Db_SqlFactory', array('getSelectStatementForContentElements', 'getUpdateStatementForContentElements'));
        $sqlFactory->expects($this->exactly(4))->method('getSelectStatementForContentElements');

        $mockQomFactory = $this->getMock('Tx_Extbase_Persistence_QOM_QueryObjectModelFactory');
        $mockQuerySettings = $this->getMock('Tx_Extbase_Persistence_Typo3QuerySettings');
        $mockQuery = $this->getMock('Tx_Extbase_Persistence_Query', array('execute', 'getQuerySettings'));
        $mockQuery->expects($this->any())->method('execute')->will($this->returnValue(array()));
        $mockQuery->expects($this->any())->method('getQuerySettings')->will($this->returnValue($mockQuerySettings));
        $mockQuery->injectQomFactory($mockQomFactory);

        $mockQueryFactory = $this->getMock('Tx_Extbase_Persistence_QueryFactory', array('create'));
        $mockQueryFactory->expects($this->any())->method('create')->will($this->returnValue($mockQuery));

        $this->featureFlag = new Tx_FeatureFlag_Domain_Repository_FeatureFlag();
        $this->featureFlag->injectQueryFactory($mockQueryFactory);
        $this->featureFlag->injectSqlFactory($sqlFactory);
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        unset($this->featureFlag);
    }

    /**
     * @test
     */
    public function factoryShouldBeCalled()
    {
        $this->featureFlag->updateFeatureFlagStatusForTable('my_table');
    }
}