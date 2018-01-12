<?php
namespace Aoe\FeatureFlag\Tests\Unit\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 AOE media GmbH <dev@aoe.com>
 *  All rights reserved
 *
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Aoe\FeatureFlag\Service\EidProcessor;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * @package FeatureFlag
 * @subpackage Tests
 */
class EidProcessorTest extends UnitTestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getServiceMock()
    {
        return $this->getMock(
            'Tx_FeatureFlag_Service', ['updateFeatureFlag'], [], '', false
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getCacheManagerMock()
    {
        return $this->getMock(
            'Tx_FeatureFlag_System_Typo3_CacheManager', ['clearPageCache'], [], '', false
        );
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
        $serviceMock->expects($this->once())
            ->method('updateFeatureFlag')
            ->with($this->equalTo($feature), $this->equalTo($expected));

        $eidProcessor = new EidProcessor($serviceMock, $this->getCacheManagerMock());
        $eidProcessor->processRequest();
    }

    /**
     * @test
     */
    public function shouldThrowExceptionTest()
    {
        $_GET = ['action' => '', 'feature' => 'testfeature'];
        $eidProcessor = new EidProcessor($this->getServiceMock(), $this->getCacheManagerMock());

        $this->setExpectedException(\Tx_FeatureFlag_Service_Exception_ActionNotFound::class);
        $eidProcessor->processRequest();
    }
}