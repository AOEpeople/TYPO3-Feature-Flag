<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 AOE GmbH <dev@aoe.com>
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
 * @subpackage Tests_System_Typo3_Task
 */
class Tx_FeatureFlag_Tests_Unit_System_Typo3_Task_FlagEntriesTest extends Tx_FeatureFlag_Tests_Unit_BaseTest
{
    /**
     * @test
     */
    public function execute()
    {
        $mockRepository = $this
            ->getMockBuilder('Tx_FeatureFlag_Domain_Repository_FeatureFlag')
            ->setMethods(['updateFeatureFlagStatusForTable'])
            ->getMock();
        $mockRepository->expects($this->exactly(2))->method('updateFeatureFlagStatusForTable')->with(
            $this->stringStartsWith('table')
        );

        $mockPersistenceManager = $this
            ->getMockBuilder('\TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface')->getMock();

        $mockConfiguration = $this
            ->getMockBuilder('Tx_FeatureFlag_System_Typo3_Configuration')
            ->setMethods(['getTables'])
            ->getMock();
        $mockConfiguration->expects($this->once())->method('getTables')->willReturn(
            [
                'table_one',
                'table_two'
            ]
        );

        $flagEntries = $this
            ->getMockBuilder('Tx_FeatureFlag_System_Typo3_Task_FlagEntries')
            ->setMethods(['getFeatureFlagService'])
            ->disableOriginalConstructor()
            ->getMock();

        $serviceMock = $this->getMockBuilder('Tx_FeatureFlag_Service')->setConstructorArgs(array(
            $mockRepository,
            $mockPersistenceManager,
            $mockConfiguration
        ))->setMethods(array('getFeatureFlagService'))->getMock();

        $flagEntries->expects($this->any())->method('getFeatureFlagService')->will(
            $this->returnValue($serviceMock)
        );

        /** @var Tx_FeatureFlag_System_Typo3_Task_FlagEntries $flagEntries */
        $this->assertTrue($flagEntries->execute());
    }
}
