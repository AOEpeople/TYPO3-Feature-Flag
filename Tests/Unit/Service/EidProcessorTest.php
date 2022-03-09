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

use Aoe\FeatureFlag\Service\EidProcessorService;
use Aoe\FeatureFlag\Service\Exception\ActionNotFoundException;
use Aoe\FeatureFlag\Service\FeatureFlagService;
use Aoe\FeatureFlag\System\Typo3\CacheManager;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class EidProcessorTest extends UnitTestCase
{
    /**
     * @return FeatureFlagService|MockObject
     */
    private function getServiceMock()
    {
        return $this
            ->getMockBuilder(FeatureFlagService::class)
            ->setMethods(['updateFeatureFlag'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return MockObject
     */
    private function getCacheManagerMock()
    {
        return $this
            ->getMockBuilder(CacheManager::class)
            ->setMethods(['clearPageCache'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return array
     */
    public function featureProvider()
    {
        return [
            ['activate', 'testfeature', true],
            ['deactivate', 'testfeature', false]
        ];
    }

    /**
     * @test
     * @dataProvider featureProvider
     */
    public function shouldProcessRequestTest($action, $feature, $expected)
    {
        $_GET = ['action' => $action, 'feature' => $feature];

        $serviceMock = $this->getServiceMock();
        $serviceMock->expects(self::once())
            ->method('updateFeatureFlag')
            ->with($this->equalTo($feature), $this->equalTo($expected));

        $eidProcessor = new EidProcessorService($serviceMock, $this->getCacheManagerMock());
        $eidProcessor->processRequest();
        $this->expectOutputRegex('/200/');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionTest()
    {
        $_GET = ['action' => '', 'feature' => 'testfeature'];
        $eidProcessor = new EidProcessorService($this->getServiceMock(), $this->getCacheManagerMock());

        $this->expectException(ActionNotFoundException::class);
        $eidProcessor->processRequest();
    }
}