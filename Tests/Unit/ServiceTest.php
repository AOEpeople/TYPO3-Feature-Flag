<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
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
 */
class Tx_FeatureFlag_Tests_Unit_ServiceTest extends Tx_FeatureFlag_Tests_Unit_BaseTest
{
    /**
     * @var Tx_FeatureFlag_Service
     */
    private $service;

    /**
     * @param Tx_FeatureFlag_Domain_Repository_FeatureFlag $mockRepository
     */
    protected function setService(Tx_FeatureFlag_Domain_Repository_FeatureFlag $mockRepository)
    {
        $mockPersistenceManager = $this->getMockBuilder('\TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface')->getMock();
        $this->service = new Tx_FeatureFlag_Service($mockRepository, $mockPersistenceManager, $this->getMockConfiguration());
    }

    /**
     * @param $isEnabled
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockModel($isEnabled)
    {
        $mockModel = $this->getMockBuilder('Tx_FeatureFlag_Domain_Model_FeatureFlag')
            ->setMockClassName('Tx_FeatureFlag_Domain_Model_FeatureFlag_Mock')->setMethods(array('setEnabled', 'isEnabled'))->getMock();

        $mockModel->expects($this->any())->method('isEnabled')->willReturn($isEnabled);

        return $mockModel;
    }

    /**
     * @param boolean $isEnabled
     */
    private function getMockRepository($isEnabled)
    {
        $mockModel = $this->getMockModel($isEnabled);
        $mockRepository = $this->getMockBuilder('Tx_FeatureFlag_Domain_Repository_FeatureFlag')
            ->setMockClassName('Tx_FeatureFlag_Domain_Repository_FeatureFlag_Mock')->getMock();
        $mockRepository->expects($this->any())->method('findByFlag')->will($this->returnValue($mockModel));

        return $mockRepository;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockConfiguration()
    {
        $mockConfiguration = $this->getMockBuilder('Tx_FeatureFlag_System_Typo3_Configuration')->setMethods(['getTables'])->getMock();
        $mockConfiguration->expects($this->any())->method('getTables')->willReturn('pages');

        return $mockConfiguration;
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
        $GLOBALS['TCA']['tx_featureflag_domain_model_featureflag'] = 'mockedTca';

        $this->setService($this->getMockRepository(true));
        $result = $this->service->isFeatureEnabled('my_cool_feature');
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForDisabledFeature()
    {
        $GLOBALS['TCA']['tx_featureflag_domain_model_featureflag'] = 'mockedTca';

        $this->setService($this->getMockRepository(false));
        $result = $this->service->isFeatureEnabled('my_cool_feature');
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfFlagDoesNotExist()
    {
        $GLOBALS['TCA']['tx_featureflag_domain_model_featureflag'] = 'mockedTca';

        $mockRepository = $this->getMockBuilder('Tx_FeatureFlag_Domain_Repository_FeatureFlag')->setMethods(['findByFlag'])->getMock();
        $mockRepository->expects($this->once())->method('findByFlag')->will($this->returnValue(null));
        $this->setService($mockRepository);
        $this->expectException('Tx_FeatureFlag_Service_Exception_FeatureNotFound');
        $this->service->isFeatureEnabled('my_cool_feature');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfTcaIsNotLoaded()
    {
        $GLOBALS['TCA'] = null;

        $mockRepository = $this->getMockBuilder('Tx_FeatureFlag_Domain_Repository_FeatureFlag')->setMethods(['findByFlag'])->getMock();
        $mockRepository->expects($this->never())->method('findByFlag');
        $this->setService($mockRepository);
        $this->expectException('RuntimeException');
        $this->service->isFeatureEnabled('my_cool_feature');
    }

    /**
     * @test
     */
    public function shouldUpdateFeatureFlag()
    {
        $mockModel = $this->getMockModel(false);

        $mockRepository = $this->getMockRepository(false);
        $mockRepository->expects($this->once())->method('update')->with($mockModel);

        $mockPersistenceManager = $this->getMockBuilder('\TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface')->getMock();
        $mockPersistenceManager->expects($this->once())->method('persistAll');

        $serviceMock = $this->getMockBuilder('Tx_FeatureFlag_Service')->setConstructorArgs(array(
            $mockRepository,
            $mockPersistenceManager,
            $this->getMockConfiguration()
        ))->setMethods(array('getFeatureFlag'))->getMock();
        $serviceMock->expects($this->any())->method('getFeatureFlag')->will($this->returnValue($mockModel));

        $serviceMock->expects($this->once())->method('getFeatureFlag')->with('mockFlag');
        $serviceMock->expects($this->once())->method('getFeatureFlag')->with('mockFlag');

        $serviceMock->updateFeatureFlag('mockFlag', true);
    }
}
