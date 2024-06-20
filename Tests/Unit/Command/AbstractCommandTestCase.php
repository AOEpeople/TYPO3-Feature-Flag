<?php

namespace Aoe\FeatureFlag\Tests\Unit\Command;

use Aoe\FeatureFlag\Service\FeatureFlagService;
use Aoe\FeatureFlag\Tests\Unit\BaseTestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

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

abstract class AbstractCommandTestCase extends BaseTestCase
{
    private array $activatedFeatures = [];

    private array $deactivatedFeatures = [];

    private int $countOfFlagEntries = 0;

    private array $shownInfos = [];

    public function callbackOnFlagEntries(): void
    {
        ++$this->countOfFlagEntries;
    }

    public function callbackOnShowInfo(string $info): void
    {
        $this->shownInfos[] = $info;
    }

    public function callbackOnUpdateFeature(string $feature, bool $enabled): void
    {
        if ($enabled) {
            $this->activatedFeatures[] = $feature;
        } else {
            $this->deactivatedFeatures[] = $feature;
        }
    }

    abstract public function testShouldRunCommand();

    /**
     * check, that entries are flagged
     */
    protected function assertThatEntriesAreFlagged()
    {
        $this->assertSame(1, $this->countOfFlagEntries);
    }

    protected function assertThatFeaturesAreActivated(array $expectedFeatures)
    {
        $this->assertSame($expectedFeatures, $this->activatedFeatures);
    }

    /**
     * Check, that no feature is activated
     */
    protected function assertThatFeaturesAreNotActivated()
    {
        $this->assertThatFeaturesAreActivated([]);
    }

    protected function assertThatFeaturesAreDeactivated(array $expectedFeatures)
    {
        $this->assertSame($expectedFeatures, $this->deactivatedFeatures);
    }

    /**
     * Check, that no feature is deactivated
     */
    protected function assertThatFeaturesAreNotDeactivated()
    {
        $this->assertThatFeaturesAreDeactivated([]);
    }

    protected function assertThatInfosAreShown(array $expectedInfos)
    {
        $this->assertSame($expectedInfos, $this->shownInfos);
    }

    protected function runCommand(string $commandClass, string $commaSeparatedListOfFeatures = '')
    {
        $_SERVER['argv'] = ['/typo3/cms-cli/typo3'];
        if (($commaSeparatedListOfFeatures === '' || $commaSeparatedListOfFeatures === '0') === false) {
            $_SERVER['argv'][] = $commaSeparatedListOfFeatures;
        }

        $input = new ArgvInput();
        $output = new ConsoleOutput();

        $featureFlagService = $this->getMockBuilder(FeatureFlagService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $featureFlagService
            ->method('flagEntries')
            ->willReturnCallback(function (): void {
                $this->callbackOnFlagEntries();
            });

        $featureFlagService
            ->method('updateFeatureFlag')
            ->willReturnCallback(function (string $feature, bool $enabled): void {
                $this->callbackOnUpdateFeature($feature, $enabled);
            });

        $command = $this
            ->getMockBuilder($commandClass)
            ->setConstructorArgs([$featureFlagService])
            ->onlyMethods(['showInfo'])
            ->getMock();
        $command
            ->method('showInfo')
            ->willReturnCallback(function (string $info): void {
                $this->callbackOnShowInfo($info);
            });

        $returnCode = $command->run($input, $output);
        $this->assertSame(0, $returnCode);
    }
}
