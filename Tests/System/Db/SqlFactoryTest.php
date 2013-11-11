<?php

/**
 * Tx_FeatureFlag_System_Db_SqlFactoryTest test case.
 */
class Tx_FeatureFlag_System_Db_SqlFactoryTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_FeatureFlag_System_Db_SqlFactory
     */
    private $sqlFactory;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->sqlFactory = new Tx_FeatureFlag_System_Db_SqlFactory();
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        unset($this->sqlFactory);
    }

    /**
     * @test
     */
    public function getSelectStatementForContentElements()
    {
        $expected = "SELECT my_table.uid FROM my_table,tx_featureflag_mapping,tx_featureflag_domain_model_featureflag WHERE tx_featureflag_mapping.uid_foreign = tx_featureflag_domain_model_featureflag.uid AND my_table.uid = tx_featureflag_domain_model_featureflag.uid_local AND tx_featureflag_domain_model_featureflag.enabled = 1 AND tx_featureflag_mapping.local_table = 'my_table' AND tx_featureflag_mapping.local_column = 'my_column'";
        $actual = $this->sqlFactory->getSelectStatementForContentElements('my_table', 'my_column', 1);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function getUpdateStatementForContentElements()
    {
        $expected = "UPDATE my_table SET hidden = ? WHERE uid IN (?);";
        $actual = $this->sqlFactory->getUpdateStatementForContentElements('my_table');
        $this->assertEquals($expected, $actual);
    }
}