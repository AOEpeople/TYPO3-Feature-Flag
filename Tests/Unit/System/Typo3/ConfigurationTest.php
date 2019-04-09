<?php
namespace Aoe\FeatureFlag\Tests\Unit\System\Typo3;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 AOE GmbH <dev@aoe.com>
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

use Aoe\FeatureFlag\System\Typo3\Configuration;
use Aoe\FeatureFlag\Tests\BaseTest;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @package FeatureFlag
 * @subpackage Tests_System_Typo3
 */
class ConfigurationTest extends BaseTest
{

    /**
     * @var MockObject
     */
    private $extConfiguation;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        unset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['feature_flag']);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->extConfiguation = $this->getMockBuilder(ExtensionConfiguration::class)->getMock();
    }

    /**
     * @test
     */
    public function methodGetShouldThrowException()
    {
        $configuration = $this->getObjectManager()->get(Configuration::class, $this->extConfiguation);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration key "InvalidConfigurationKey" does not exist.');
        $this->expectExceptionCode(1384161387);
        $configuration->get('InvalidConfigurationKey');
    }

    /**
     * @test
     */
    public function methodGetShouldReturnCorrectConfiguration()
    {
        $mockedConf = array(
                'test_conf_key' => 'this_value_must_be_returned'
            );
        $this->extConfiguation->expects($this->any())->method('get')->willReturn($mockedConf);
        $configuration = $this->getObjectManager()->get(Configuration::class, $this->extConfiguation);
        $this->assertEquals('this_value_must_be_returned', $configuration->get('test_conf_key'));
    }

    /**
     * @test
     */
    public function getTablesShouldReturnAnArray()
    {
        $mockedConf = array(
            'tables' => 'pages,tt_content,foo,bar'
        );
        $this->extConfiguation->expects($this->any())->method('get')->willReturn($mockedConf);

        $configuration = $this->getObjectManager()->get(Configuration::class, $this->extConfiguation);
        $this->assertTrue(is_array($configuration->getTables()));
        $this->assertCount(4, $configuration->getTables());
    }

    private function getObjectManager(){
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}
