<?php
namespace Aoe\FeatureFlag\Tests\Unit\Service;

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

use Aoe\FeatureFlag\Domain\Model\FeatureFlag;
use Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository;
use Aoe\FeatureFlag\Service\Exception\FeatureNotFoundException;
use Aoe\FeatureFlag\Service\FeatureFlagService;
use Aoe\FeatureFlag\System\Typo3\Configuration;
use Aoe\FeatureFlag\Tests\Unit\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

class FeatureFlagServiceTest extends BaseTest
{
    /**
     * @var FeatureFlagService
     */
    private $service;

    /**
     * @param FeatureFlagRepository $mockRepository
     */
    protected function setService(FeatureFlagRepository $mockRepository)
    {
        $mockPersistenceManager = $this->getMockBuilder(PersistenceManagerInterface::class)->getMock();
        $this->service = new FeatureFlagService(
            $mockRepository,
            $mockPersistenceManager,
            $this->getMockConfiguration()
        );
    }

    /**
     * @param $isEnabled
     * @return MockObject
     */
    private function getMockModel($isEnabled)
    {
        $mockModel = $this->getMockBuilder(FeatureFlag::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'setEnabled',
                    'isEnabled'
                ]
            )->getMock();

        $mockModel->expects(self::any())->method('isEnabled')->willReturn($isEnabled);

        return $mockModel;
    }

    /**
     * @param boolean $isEnabled
     */
    private function getMockRepository($isEnabled)
    {
        $mockModel = $this->getMockModel($isEnabled);
        $mockRepository = $this->getMockBuilder(FeatureFlagRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockRepository->expects(self::any())->method('findByFlag')->willReturn($mockModel);

        return $mockRepository;
    }

    /**
     * @return MockObject
     */
    private function getMockConfiguration()
    {
        $mockConfiguration = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTables'])
            ->getMock();
        $mockConfiguration->expects(self::any())->method('getTables')->willReturn('pages');

        return $mockConfiguration;
    }

    /**
     * (non-PHPdoc)
     * @see TestCase::tearDown()
     */
    protected function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function shouldReturnTrueForEnabledFeature()
    {
        $GLOBALS['TCA']['tx_featureflag_domain_model_featureflag'] = 'mockedTca';

        $this->setService($this->getMockRepository(true));
        $result = $this->service->isFeatureEnabled('my_cool_feature');
        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForDisabledFeature()
    {
        $GLOBALS['TCA']['tx_featureflag_domain_model_featureflag'] = 'mockedTca';

        $this->setService($this->getMockRepository(false));
        $result = $this->service->isFeatureEnabled('my_cool_feature');
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfFlagDoesNotExist()
    {
        $GLOBALS['TCA']['tx_featureflag_domain_model_featureflag'] = 'mockedTca';

        $mockRepository = $this->getMockBuilder(FeatureFlagRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByFlag'])->getMock();
        $mockRepository->expects(self::once())->method('findByFlag')->willReturn(null);
        $this->setService($mockRepository);
        $this->expectException(FeatureNotFoundException::class);
        $this->service->isFeatureEnabled('my_cool_feature');
    }

    /**
     * @test
     */
    public function shouldUpdateFeatureFlag()
    {
        $mockModel = $this->getMockModel(false);

        $mockRepository = $this->getMockRepository(false);
        $mockRepository->expects(self::once())->method('update')->with($mockModel);

        $mockPersistenceManager =
            $this->getMockBuilder(PersistenceManagerInterface::class)->getMock();

        $mockPersistenceManager->expects(self::once())->method('persistAll');

        $serviceMock = $this->getMockBuilder(FeatureFlagService::class)->setConstructorArgs([
            $mockRepository,
            $mockPersistenceManager,
            $this->getMockConfiguration()
        ])->setMethods(['getFeatureFlag'])->getMock();
        $serviceMock->expects(self::any())->method('getFeatureFlag')->willReturn($mockModel);

        $serviceMock->expects(self::once())->method('getFeatureFlag')->with('mockFlag');
        $serviceMock->expects(self::once())->method('getFeatureFlag')->with('mockFlag');

        $serviceMock->updateFeatureFlag('mockFlag', true);
    }
}
