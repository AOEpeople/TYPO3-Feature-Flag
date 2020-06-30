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

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Lang\LanguageService;

/**
 * @package FeatureFlag
 * @subpackage Tests_System_Typo3
 */
class Tx_FeatureFlag_Tests_Functional_System_Typo3_TCATest extends FunctionalTestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $tca;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        parent::setUp();

        $this->tca = $this
            ->getMockBuilder(Tx_FeatureFlag_System_Typo3_TCA::class)
            ->setMethods([
                'getMappingRepository',
                'getFeatureFlagRepository',
                'getFeatureFlagByUid',
                'getPersistenceManager',
                'getLanguageService'
            ])
            ->getMock();
        $persistenceManager = $this
            ->getMockBuilder(PersistenceManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['persistAll'])
            ->getMock();

        $languageService = $this->getMockBuilder(LanguageService::class)
            ->disableOriginalConstructor()
            ->setMethods(['sL'])
            ->getMock();

        $this->tca->expects($this->any())->method('getPersistenceManager')->willReturn($persistenceManager);
        $this->tca->expects($this->any())->method('getLanguageService')->willReturn($languageService);
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        unset($this->tca);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function selectRendersCorrectly()
    {
        $featureFlag = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Model_FeatureFlag::class)
            ->setMethods(['getUid'])
            ->getMock();
        $featureFlag->expects($this->any())->method('getUid')->willReturn(4711);
        $mapping = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Model_Mapping::class)
            ->setMethods(['getFeatureFlag'])
            ->getMock();
        $mapping->expects($this->any())->method('getFeatureFlag')->willReturn($featureFlag);
        $mappingRepository = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Repository_Mapping::class)
            ->setMethods(['findOneByForeignTableNameAndUid'])
            ->getMock();
        $mappingRepository
            ->expects($this->once())
            ->method('findOneByForeignTableNameAndUid')
            ->willReturn($mapping);
        $featureFlagRepository = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Repository_FeatureFlag::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll'])
            ->getMock();
        $featureFlagRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($this->getListOfFeatureFlags());
        $this->tca->expects($this->once())
            ->method('getMappingRepository')
            ->willReturn($mappingRepository);
        $this->tca->expects($this->once())
            ->method('getFeatureFlagRepository')
            ->willReturn($featureFlagRepository);

        $PA = [];
        $PA['row'] = [];
        $PA['row']['uid'] = '111';
        $PA['table'] = 'pages';
        $PA['itemFormElID'] = 'itemFormElID';
        $PA['itemFormElName'] = 'itemFormElName';

        $content = $this->tca->renderSelectForFlag($PA);

        $this->assertContains('<option value="0"></option>', $content);
        $this->assertContains('<option value="111">flag 1</option>', $content);
        $this->assertContains('<option value="4711" selected="selected">flag 2</option>', $content);
        $this->assertContains('<option value="222">flag 3</option>', $content);
    }

    /**
     * @test
     */
    public function processDatamapDoNothingIfNothingSelected()
    {
        $mappingRepository = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Repository_Mapping::class)
            ->setMethods(['findOneByForeignTableNameAndUid', 'add', 'remove', 'update'])
            ->getMock();
        $mappingRepository
            ->expects($this->once())
            ->method('findOneByForeignTableNameAndUid')
            ->willReturn(null);
        $mappingRepository->expects($this->never())->method('add');
        $mappingRepository->expects($this->never())->method('remove');
        $mappingRepository->expects($this->never())->method('update');
        $this->tca->expects($this->once())->method('getMappingRepository')->willReturn($mappingRepository);
        $this->tca->expects($this->never())->method('getFeatureFlagByUid');

        $tceMainMock = $this->createMock(DataHandler::class);
        $incomingFieldArray = [
            'tx_featureflag_flag' => '0',
            'tx_featureflag_behavior' => '0',
        ];
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', '4711', $tceMainMock);
    }

    /**
     * @test
     */
    public function processDatamapDoNothingIfNotInFeatureFlagContext()
    {
        $mappingRepository = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Repository_Mapping::class)
            ->setMethods(['findOneByForeignTableNameAndUid', 'add', 'remove', 'update'])
            ->getMock();
        $mappingRepository
            ->expects($this->never())
            ->method('findOneByForeignTableNameAndUid')
            ->willReturn(null);
        $mappingRepository->expects($this->never())->method('add');
        $mappingRepository->expects($this->never())->method('remove');
        $mappingRepository->expects($this->never())->method('update');
        $this->tca->expects($this->never())->method('getMappingRepository')->willReturn($mappingRepository);
        $this->tca->expects($this->never())->method('getFeatureFlagByUid');

        $tceMainMock = $this->getMockBuilder(DataHandler::class)->getMock();
        $incomingFieldArray = ['hidden' => '0'];
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', '4711', $tceMainMock);
    }

    /**
     * @test
     */
    public function processDatamapRemoveMappingIfNothingSelectedAndMappingExists()
    {
        $mapping = $this->createMock(Tx_FeatureFlag_Domain_Model_Mapping::class);
        $mappingRepository = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Repository_Mapping::class)
            ->setMethods(['findOneByForeignTableNameAndUid', 'remove', 'update'])
            ->getMock();
        $mappingRepository
            ->expects($this->once())
            ->method('findOneByForeignTableNameAndUid')
            ->willReturn($mapping);
        $mappingRepository->expects($this->once())->method('remove');
        $mappingRepository->expects($this->once())->method('update');
        $this->tca->expects($this->any())->method('getMappingRepository')->willReturn($mappingRepository);

        $tceMainMock = $this->createMock(DataHandler::class);
        $incomingFieldArray = [
            'tx_featureflag_flag' => '0',
            'tx_featureflag_behavior' => '0',
        ];
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', '4711', $tceMainMock);
    }

    /**
     * @test
     */
    public function processDatamapCreateNewMappingIfFeatureFlagGivenAndNoMappingPreviouslyCreated()
    {
        $featureFlag = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Model_FeatureFlag::class)
            ->setMethods(['getUid'])
            ->getMock();
        $featureFlag->expects($this->any())->method('getUid')->willReturn(4711);

        $mappingRepository = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Repository_Mapping::class)
            ->setMethods(['findOneByForeignTableNameAndUid', 'add'])
            ->getMock();
        $mappingRepository
            ->expects($this->once())
            ->method('findOneByForeignTableNameAndUid')
            ->willReturn(null);
        $mappingRepository->expects($this->once())->method('add');

        $this->tca->expects($this->any())->method('getMappingRepository')->willReturn($mappingRepository);
        $this->tca->expects($this->any())->method('getFeatureFlagByUid')->willReturn($featureFlag);

        $tceMainMock = $this->createMock(DataHandler::class);
        $incomingFieldArray = [
            'tx_featureflag_flag' => '4711',
            'tx_featureflag_behavior' => '0',
        ];
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', '123', $tceMainMock);
    }

    /**
     * @test
     */
    public function processCmdmapCommandIsNotDelete()
    {
        $this->tca->expects($this->never())->method('getMappingRepository');
        $this->tca->processCmdmap_postProcess('not_delete', 'my_table', '4711');
    }

    /**
     * @test
     */
    public function processCmdmappostIsDelete()
    {
        $mappingRepository = $this->getMockBuilder(Tx_FeatureFlag_Domain_Repository_Mapping::class)
            ->setMethods(['findAllByForeignTableNameAndUid', 'remove'])
            ->getMock();
        $mappingRepository
            ->expects($this->once())
            ->method('findAllByForeignTableNameAndUid')
            ->willReturn($this->getListOfMappings());
        $mappingRepository->expects($this->exactly(2))->method('remove');
        $this->tca->expects($this->any())->method('getMappingRepository')->willReturn($mappingRepository);

        $this->tca->processCmdmap_postProcess('delete', 'my_table', '4711');
    }

    /**
     * @return array
     */
    protected function getListOfMappings()
    {
        $mapping1 = $this->createMock(Tx_FeatureFlag_Domain_Model_Mapping::class);
        $mapping2 = $this->createMock(Tx_FeatureFlag_Domain_Model_Mapping::class);
        $mapping3 = $this->createMock('stdClass');

        return array($mapping1, $mapping2, $mapping3);
    }

    /**
     * @return array
     */
    protected function getListOfFeatureFlags()
    {
        $featureFlag1 = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Model_FeatureFlag::class)
            ->setMethods(['getUid', 'getDescription'])
            ->getMock();
        $featureFlag1->expects($this->any())->method('getUid')->willReturn(111);
        $featureFlag1->expects($this->any())->method('getDescription')->willReturn('flag 1');
        $featureFlag2 = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Model_FeatureFlag::class)
            ->setMethods(['getUid', 'getDescription'])
            ->getMock();
        $featureFlag2->expects($this->any())->method('getUid')->willReturn(4711);
        $featureFlag2->expects($this->any())->method('getDescription')->willReturn('flag 2');
        $featureFlag3 = $this
            ->getMockBuilder(Tx_FeatureFlag_Domain_Model_FeatureFlag::class)
            ->setMethods(['getUid', 'getDescription'])
            ->getMock();
        $featureFlag3->expects($this->any())->method('getDescription')->willReturn('flag 3');
        $featureFlag3->expects($this->any())->method('getUid')->willReturn(222);

        return [$featureFlag1, $featureFlag2, $featureFlag3];
    }
}
