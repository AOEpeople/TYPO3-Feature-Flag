<?php

/**
 * Tx_FeatureFlag_System_Typo3_Task_FlagEntries test case.
 */
class Tx_FeatureFlag_System_Typo3_Task_FlagEntriesTest extends Tx_Phpunit_TestCase
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
        $mockRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_FeatureFlag', array('updateFeatureFlagStatusForTable'));
        $mockRepository->expects($this->exactly(2))->method('updateFeatureFlagStatusForTable')->with($this->stringStartsWith('table'));
        $mockConfiguration = $this->getMock('Tx_FeatureFlag_System_Typo3_Configuration', array('getTables'));
        $mockConfiguration->expects($this->once())->method('getTables')->will($this->returnValue(array('table_one', 'table_two')));
        $this->flagEntries = $this->getMock('Tx_FeatureFlag_System_Typo3_Task_FlagEntries', array('getFeatureFlagRepository', 'getConfiguration'));
        $this->flagEntries->expects($this->any())->method('getFeatureFlagRepository')->will($this->returnValue($mockRepository));
        $this->flagEntries->expects($this->any())->method('getConfiguration')->will($this->returnValue($mockConfiguration));
        $this->assertTrue($this->flagEntries->execute());
    }
}