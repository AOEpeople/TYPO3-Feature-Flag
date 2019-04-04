<?php
namespace Aoe\FeatureFlag\Tests;

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

use Aoe\FeatureFlag\Domain\Model\FeatureFlag;
use Aoe\FeatureFlag\Domain\Repository\FeatureFlag as FeatureFlagRepository;
use Aoe\FeatureFlag\Service;
use Aoe\FeatureFlag\System\Typo3\Configuration;
use RuntimeException;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

/**
 * @package FeatureFlag
 * @subpackage Tests
 */
class ServiceTest extends BaseTest
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @param FeatureFlagRepository $mockRepository
     */
    protected function setService(FeatureFlagRepository $mockRepository)
    {
        $mockPersistenceManager = $this->getMockBuilder(PersistenceManagerInterface::class)->getMock();
        $this->service = new Service($mockRepository, $mockPersistenceManager, $this->getMockConfiguration());
    }

    /**
     * @param $isEnabled
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockModel($isEnabled)
    {
        $mockModel = $this->getMockBuilder(FeatureFlag::class)->setMethods(array('setEnabled', 'isEnabled'))->getMock();

        $mockModel->expects($this->any())->method('isEnabled')->willReturn($isEnabled);

        return $mockModel;
    }

    /**
     * @param boolean $isEnabled
     */
    private function getMockRepository($isEnabled)
    {
        $mockModel = $this->getMockModel($isEnabled);
        $mockRepository = $this->getMockBuilder(FeatureFlagRepository::class)->getMock();
        $mockRepository->expects($this->any())->method('findByFlag')->will($this->returnValue($mockModel));

        return $mockRepository;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockConfiguration()
    {
        $mockConfiguration = $this->getMockBuilder(Configuration::class)->getMock();
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

        $mockRepository = $this->getMockBuilder(\Aoe\FeatureFlag\Domain\Repository\FeatureFlag::class)->getMock();
        $mockRepository->expects($this->once())->method('findByFlag')->will($this->returnValue(null));
        $this->setService($mockRepository);
        $this->expectException(Service\Exception\FeatureNotFound::class);
        $this->service->isFeatureEnabled('my_cool_feature');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfTcaIsNotLoaded()
    {
        $GLOBALS['TCA'] = null;

        $mockRepository = $this->getMockBuilder(\Aoe\FeatureFlag\Domain\Repository\FeatureFlag::class)->getMock();
        $mockRepository->expects($this->never())->method('findByFlag');
        $this->setService($mockRepository);
         $this->expectException(RuntimeException::class);
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

        $mockPersistenceManager = $this->getMockBuilder(PersistenceManagerInterface::class)->getMock();
        $mockPersistenceManager->expects($this->once())->method('persistAll');


        $serviceMock = $this->getMockBuilder(Service::class)->setConstructorArgs(array(
            $mockRepository,
            $mockPersistenceManager,
            $this->getMockConfiguration()
        ))->setMethods(array('getFeatureFlag'))->getMock();
        $serviceMock->expects($this->any())->method('getFeatureFlag')->will($this->returnValue($mockModel));

        $serviceMock->expects($this->once())->method('getFeatureFlag')->with('mockFlag');
        $serviceMock->expects($this->once())->method('getFeatureFlag')->with('mockFlag');

        $serviceMock->updateFeatureFlag('mockFlag', true);
    }

    /**
     * @test
     */
    public function shouldFlagEntries()
    {
        $this->markTestIncomplete("will be added soon");
    }
}
