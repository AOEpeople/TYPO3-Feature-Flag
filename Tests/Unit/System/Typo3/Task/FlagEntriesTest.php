<?php
namespace Aoe\FeatureFlag\Tests\Unit\System\Typo3\Task;

use Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository;
use Aoe\FeatureFlag\Service\FeatureFlagService;
use Aoe\FeatureFlag\System\Typo3\Configuration;
use Aoe\FeatureFlag\System\Typo3\Task\FlagEntriesTask;
use Aoe\FeatureFlag\Tests\Unit\BaseTest;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

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

class FlagEntriesTest extends BaseTest
{
    /**
     * @test
     */
    public function execute()
    {
        $mockRepository = $this
            ->getMockBuilder(FeatureFlagRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['updateFeatureFlagStatusForTable'])
            ->getMock();
        $mockRepository->expects($this->exactly(2))->method('updateFeatureFlagStatusForTable')
            ->with($this->stringStartsWith('table'));

        $mockPersistenceManager = $this->getMockBuilder(PersistenceManagerInterface::class)->getMock();

        $mockConfiguration = $this
            ->getMockBuilder(Configuration::class)
            ->setMethods(['getTables'])
            ->getMock();
        $mockConfiguration->expects($this->once())->method('getTables')->willReturn(
            [
                'table_one',
                'table_two'
            ]
        );

        $flagEntries = $this
            ->getMockBuilder(FlagEntriesTask::class)
            ->setMethods(['getFeatureFlagService'])
            ->disableOriginalConstructor()
            ->getMock();

        $serviceMock = $this->getMockBuilder(FeatureFlagService::class)
            ->setConstructorArgs(
                [
                    $mockRepository,
                    $mockPersistenceManager,
                    $mockConfiguration
                ]
            )->setMethods(['getFeatureFlagService'])->getMock();

        $flagEntries->expects($this->any())->method('getFeatureFlagService')->willReturn($serviceMock);

        /** @var FlagEntriesTask $flagEntries */
        $this->assertTrue($flagEntries->execute());
    }
}
