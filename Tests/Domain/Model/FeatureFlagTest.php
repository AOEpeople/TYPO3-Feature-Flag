<?php

require_once dirname(__FILE__) . '/../../../Classes/Domain/Model/FeatureFlag.php';
require_once dirname(__FILE__) . '/../../../../phpunit/Classes/TestCase.php';

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
        $this->featureFlag->setName('my_new_feature_flag');
        $this->featureFlag->setEnabled(true);
        $this->assertTrue($this->featureFlag->isEnabled());
        $this->assertEquals($this->featureFlag->getName(), 'my_new_feature_flag');
    }
}