<?php

require_once dirname(__FILE__) . '/../../Classes/System/Configuration.php';
require_once dirname(__FILE__) . '/../../../phpunit/Classes/TestCase.php';

/**
 * Tx_FeatureFlag_System_ConfigurationTest test case.
 */
class Tx_FeatureFlag_System_ConfigurationTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_FeatureFlag_System_Configuration
     */
    private $configuration;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $GLOBALS['TYPO3_CONF_VARS'] = array(
            'EXT' => array(
                'extConf' => array(
                    'feature_flag' => serialize(array('foo' => 'bar')),
                ),
            )
        );
        $this->configuration = new Tx_FeatureFlag_System_Configuration();
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->configuration = null;
        unset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);
    }

    /**
     * @test
     */
    public function getConfigurationValueByKey()
    {
        $this->assertEquals('bar', $this->configuration->getValueForKey('foo'));
    }

    /**
     * @test
     */
    public function getConfigurationValueByKeyMustThrowException()
    {
        $this->setExpectedException('InvalidArgumentException', 'Key not found: "does_not_exist"', 1383821261);
        $this->configuration->getValueForKey('does_not_exist');
    }
}