<?php

/**
 * Tx_FeatureFlag_Service test case.
 */
class Tx_FeatureFlag_ServiceTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_FeatureFlag_Service
     */
    private $service;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->service = new Tx_FeatureFlag_Service();
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->service = null;
    }

    /**
     * @test
     */
    public function shouldReturnTrueForEnabledFeature()
    {
        $this->injectMockRepository(true);
        $result = $this->service->isFeatureEnabled('my_cool_feature');
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForDisabledFeature()
    {
        $this->injectMockRepository(false);
        $result = $this->service->isFeatureEnabled('my_cool_feature');
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfFlagDoesNotExist()
    {
        $mockRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_FeatureFlag', array('findByFlag'));
        $mockRepository->expects($this->once())->method('findByFlag')->will($this->throwException(new Exception()));
        $this->service->injectFeatureFlagRepository($mockRepository);
        $this->setExpectedException('Tx_FeatureFlag_Service_Exception_FeatureNotFound');
        $this->service->isFeatureEnabled('my_cool_feature');
    }

    /**
     * @param boolean $isEnabled
     */
    private function injectMockRepository($isEnabled)
    {
        $mockModel = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('isEnabled'));
        $mockModel->expects($this->once())->method('isEnabled')->will($this->returnValue($isEnabled));
        $mockRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_FeatureFlag', array('findByFlag'));
        $mockRepository->expects($this->once())->method('findByFlag')->will($this->returnValue($mockModel));
        $this->service->injectFeatureFlagRepository($mockRepository);
    }
}