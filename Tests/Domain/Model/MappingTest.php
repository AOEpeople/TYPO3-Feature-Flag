<?php

/**
 * Tx_FeatureFlag_Domain_Model_Mapping test case.
 */
class Tx_FeatureFlag_Domain_Model_MappingTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_FeatureFlag_Domain_Model_Mapping
     */
    private $mapping;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->mapping = new Tx_FeatureFlag_Domain_Model_Mapping();
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->mapping = null;
    }

    /**
     * @test
     */
    public function tstamp()
    {
        $this->mapping->setTstamp(2183466346);
        $this->assertEquals($this->mapping->getTstamp(), 2183466346);
    }

    /**
     * @test
     */
    public function crdate()
    {
        $this->mapping->setCrdate(2183466347);
        $this->assertEquals($this->mapping->getCrdate(), 2183466347);
    }

    /**
     * @test
     */
    public function featureFlag()
    {
        $featureFlag = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getFlag'));
        $featureFlag->expects($this->any())->method('getFlag')->will($this->returnValue('my_awesome_feature_flag'));
        $this->mapping->setFeatureFlag($featureFlag);
        $this->assertEquals($this->mapping->getFeatureFlag()->getFlag(), 'my_awesome_feature_flag');
    }

    /**
     * @test
     */
    public function foreignTableColumn()
    {
        $this->mapping->setForeignTableColumn('my_foreign_column');
        $this->assertEquals($this->mapping->getForeignTableColumn(), 'my_foreign_column');
    }

    /**
     * @test
     */
    public function foreignTableName()
    {
        $this->mapping->setForeignTableName('my_foreign_table');
        $this->assertEquals($this->mapping->getForeignTableName(), 'my_foreign_table');
    }

    /**
     * @test
     */
    public function foreignTableUid()
    {
        $this->mapping->setForeignTableUid(4711);
        $this->assertEquals($this->mapping->getForeignTableUid(), 4711);
    }
}