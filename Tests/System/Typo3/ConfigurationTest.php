<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 AOE GmbH <dev@aoemedia.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package FeatureFlag
 * @subpackage Tests_System_Typo3
 * @author Kevin Schu <kevin.schu@aoemedia.de>
 * @author Matthias Gutjahr <matthias.gutjahr@aoemedia.de>
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
        $this->setExpectedException(
            'InvalidArgumentException',
            'Configuration key "InvalidConfigurationKey" does not exist.',
            1384161387
        );
        $configuration->get('InvalidConfigurationKey');
    }

    /**
     * @test
     */
    public function methodGetShouldReturnCorrectConfiguration()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag'] = serialize(
            array(
                'test_conf_key' => 'this_value_must_be_returned'
            )
        );
        $configuration = new Tx_FeatureFlag_System_Typo3_Configuration();
        $this->assertEquals('this_value_must_be_returned', $configuration->get('test_conf_key'));
    }

    /**
     * @test
     */
    public function getTablesShouldReturnAnArray()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag'] = serialize(
            array(
                'tables' => 'pages,tt_content,foo,bar'
            )
        );
        $configuration = new Tx_FeatureFlag_System_Typo3_Configuration();
        $this->assertTrue(is_array($configuration->getTables()));
        $this->assertCount(4, $configuration->getTables());
    }
}