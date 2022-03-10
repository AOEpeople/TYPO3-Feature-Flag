<?php
namespace Aoe\FeatureFlag\Tests\Functional\System\Typo3;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 AOE GmbH <dev@aoe.com>
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
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use PHPUnit\Framework\Constraint\IsType;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ConfigurationTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/feature_flag'];

    /**
     * @test
     */
    public function methodGetShouldThrowException()
    {
        $configuration = GeneralUtility::makeInstance(Configuration::class);
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Configuration key "InvalidConfigurationKey" does not exist.');
        $this->expectExceptionCode(1384161387);
        $configuration->get('InvalidConfigurationKey');
    }

    /**
     * @test
     */
    public function methodGetShouldReturnCorrectConfiguration()
    {
        $configuration = GeneralUtility::makeInstance(Configuration::class);
        $this->assertEquals('tt_content,pages,sys_template', $configuration->get('tables'));
    }

    /**
     * @test
     */
    public function getTablesShouldReturnAnArray()
    {
        $configuration = GeneralUtility::makeInstance(Configuration::class);
        $this->assertThat($configuration->getTables(), new IsType('array'));
        $this->assertEquals(['tt_content', 'pages', 'sys_template'], $configuration->getTables());
    }
}
