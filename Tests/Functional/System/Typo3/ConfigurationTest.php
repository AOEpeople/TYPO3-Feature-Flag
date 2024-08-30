<?php

namespace Aoe\FeatureFlag\Tests\Functional\System\Typo3;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2024 AOE GmbH <dev@aoe.com>
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
use PHPUnit\Framework\Constraint\IsType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ConfigurationTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/feature_flag'];

    public function testMethodGetShouldThrowException(): void
    {
        $configuration = GeneralUtility::makeInstance(Configuration::class);
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Configuration key "InvalidConfigurationKey" does not exist.');
        $this->expectExceptionCode(1384161387);
        $configuration->get('InvalidConfigurationKey');
    }

    public function testMethodGetShouldReturnCorrectConfiguration(): void
    {
        $configuration = GeneralUtility::makeInstance(Configuration::class);
        $this->assertSame('tt_content,pages,sys_template', $configuration->get('tables'));
    }

    public function testGetTablesShouldReturnAnArray(): void
    {
        $configuration = GeneralUtility::makeInstance(Configuration::class);
        $this->assertThat($configuration->getTables(), new IsType('array'));
        $this->assertSame(['tt_content', 'pages', 'sys_template'], $configuration->getTables());
    }
}
