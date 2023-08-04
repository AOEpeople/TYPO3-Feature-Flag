<?php
namespace Aoe\FeatureFlag\Tests\Unit\Command;

use Aoe\FeatureFlag\Service\FeatureFlagService;
use Aoe\FeatureFlag\Tests\Unit\BaseTest;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2023 AOE GmbH <dev@aoe.com>
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

abstract class AbstractCommandTest extends BaseTest
{
    private array $activatedFeatures = [];

    private array $deactivatedFeatures = [];

    private int $countOfFlagEntries = 0;

    private array $shownInfos = [];

    public function callbackOnFlagEntries(): void
    {
        $this->countOfFlagEntries++;
    }

    public function callbackOnShowInfo(string $info): void
    {
        $this->shownInfos[] = $info;
    }

    public function callbackOnUpdateFeature(string $feature, bool $enabled): void
    {
        if ($enabled === true) {
            $this->activatedFeatures[] = $feature;
        } else {
            $this->deactivatedFeatures[] = $feature;
        }
    }

    public abstract function shouldRunCommand(): void;

    protected function assertThatEntriesAreFlagged(): void
    {
        self::assertEquals(1, $this->countOfFlagEntries);
    }

    protected function assertThatFeaturesAreActivated(array $expectedFeatures): void
    {
        self::assertEquals($expectedFeatures, $this->activatedFeatures);
    }

    protected function assertThatFeaturesAreNotActivated(): void
    {
        self::assertThatFeaturesAreActivated([]);
    }

    protected function assertThatFeaturesAreDeactivated(array $expectedFeatures): void
    {
        self::assertEquals($expectedFeatures, $this->deactivatedFeatures);
    }

    protected function assertThatFeaturesAreNotDeactivated(): void
    {
        self::assertThatFeaturesAreDeactivated([]);
    }

    protected function assertThatInfosAreShown(array $expectedInfos): void
    {
        self::assertEquals($expectedInfos, $this->shownInfos);
    }

    protected function runCommand(string $commandClass, string $commaSeparatedListOfFeatures = ''): void
    {
        $_SERVER['argv'] = ['/typo3/cms-cli/typo3'];
        if (false === empty($commaSeparatedListOfFeatures)) {
            $_SERVER['argv'][] = $commaSeparatedListOfFeatures;
        }

        $input = new ArgvInput();
        $output = new ConsoleOutput();

        $featureFlagService = $this->getMockBuilder(FeatureFlagService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $featureFlagService
            ->expects($this->any())
            ->method('flagEntries')
            ->willReturnCallback(array($this, 'callbackOnFlagEntries'));
        $featureFlagService
            ->expects($this->any())
            ->method('updateFeatureFlag')
            ->willReturnCallback(array($this, 'callbackOnUpdateFeature'));

        $command = $this
            ->getMockBuilder($commandClass)
            ->setConstructorArgs([$featureFlagService])
            ->onlyMethods(['showInfo'])
            ->getMock();
        $command
            ->expects($this->any())
            ->method('showInfo')
            ->willReturnCallback(array($this, 'callbackOnShowInfo'));
        $returnCode = $command->run($input, $output);
        self::assertEquals(0, $returnCode);
    }
}
