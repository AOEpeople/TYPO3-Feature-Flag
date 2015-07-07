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
 * @subpackage Tests
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_FeatureFlag_ServiceTest extends Tx_FeatureFlag_Tests_BaseTest
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
        unset($this->service);
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
        $mockRepository->expects($this->once())->method('findByFlag')->will($this->returnValue(null));
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