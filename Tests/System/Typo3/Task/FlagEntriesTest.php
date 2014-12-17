<?php

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

/**
 * @package FeatureFlag
 * @subpackage Tests_System_Typo3_Task
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_FeatureFlag_System_Typo3_Task_FlagEntriesTest extends Tx_FeatureFlag_Tests_BaseTest
{
    /**
     * @var Tx_FeatureFlag_System_Typo3_Task_FlagEntries
     */
    protected $flagEntries;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->flagEntries = null;
    }

    /**
     * @test
     */
    public function execute()
    {
        $mockRepository = $this->getMock(
            'Tx_FeatureFlag_Domain_Repository_FeatureFlag',
            array('updateFeatureFlagStatusForTable')
        );
        $mockRepository->expects($this->exactly(2))->method('updateFeatureFlagStatusForTable')->with(
            $this->stringStartsWith('table')
        );
        $mockConfiguration = $this->getMock('Tx_FeatureFlag_System_Typo3_Configuration', array('getTables'));
        $mockConfiguration->expects($this->once())->method('getTables')->will(
            $this->returnValue(
                array(
                    'table_one',
                    'table_two'
                )
            )
        );
        $this->flagEntries = $this->getMock(
            'Tx_FeatureFlag_System_Typo3_Task_FlagEntries',
            array('getFeatureFlagRepository', 'getConfiguration')
        );
        $this->flagEntries->expects($this->any())->method('getFeatureFlagRepository')->will(
            $this->returnValue($mockRepository)
        );
        $this->flagEntries->expects($this->any())->method('getConfiguration')->will(
            $this->returnValue($mockConfiguration)
        );
        $this->assertTrue($this->flagEntries->execute());
    }
}