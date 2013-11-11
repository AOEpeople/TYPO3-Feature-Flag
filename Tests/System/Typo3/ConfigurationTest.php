<?php

/**
 * Tx_FeatureFlag_Service test case.
 */
class Tx_FeatureFlag_System_Typo3_ConfigurationTest extends Tx_Phpunit_TestCase
{
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        unset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);
    }

    /**
     * @test
     */
    public function methodGetShouldThrowException()
    {
        $configuration = new Tx_FeatureFlag_System_Typo3_Configuration();
        $this->setExpectedException('InvalidArgumentException', 'Configuration key "InvalidConfigurationKey" does not exist.', 1384161387);
        $configuration->get('InvalidConfigurationKey');
    }

    /**
     * @test
     */
    public function methodGetShouldReturnCorrectConfiguration()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag'] = serialize(array(
            'test_conf_key' => 'this_value_must_be_returned'
        ));
        $configuration = new Tx_FeatureFlag_System_Typo3_Configuration();
        $this->assertEquals('this_value_must_be_returned', $configuration->get('test_conf_key'));
    }

    /**
     * @test
     */
    public function getTablesShouldReturnAnArray()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag'] = serialize(array(
            'tables' => 'pages,tt_content,foo,bar'
        ));
        $configuration = new Tx_FeatureFlag_System_Typo3_Configuration();
        $this->assertTrue(is_array($configuration->getTables()));
        $this->assertCount(4, $configuration->getTables());
    }
}