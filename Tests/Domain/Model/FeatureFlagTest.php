<?php

/**
 * Tx_FeatureFlag_Domain_Model_FeatureFlag test case.
 */
class Tx_FeatureFlag_Domain_Model_FeatureFlagTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_FeatureFlag_Domain_Model_FeatureFlag
     */
    private $featureFlag;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->featureFlag = new Tx_FeatureFlag_Domain_Model_FeatureFlag();
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->featureFlag = null;
    }

    /**
     * @test
     */
    public function checkProperties()
    {
        $this->featureFlag->setDescription('This is a test description');
        $this->featureFlag->setEnabled(true);
        $this->featureFlag->setFlag('my_new_feature_flag');
        $this->assertTrue($this->featureFlag->isEnabled());
        $this->assertEquals($this->featureFlag->getDescription(), 'This is a test description');
        $this->assertEquals($this->featureFlag->getFlag(), 'my_new_feature_flag');
    }
}