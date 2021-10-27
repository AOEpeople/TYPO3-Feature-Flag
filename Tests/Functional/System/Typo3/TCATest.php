<?php
namespace Aoe\FeatureFlag\Tests\Functional\System\Typo3;

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
use Aoe\FeatureFlag\Domain\Model\Mapping;
use Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository;
use Aoe\FeatureFlag\Domain\Repository\MappingRepository;
use Aoe\FeatureFlag\System\Typo3\TCA;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class TCATest extends FunctionalTestCase
{
    /**
     * @var TCA|MockObject
     */
    protected $tca;

    /**
     * (non-PHPdoc)
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tca = $this
            ->getMockBuilder(TCA::class)
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
     * @see TestCase::tearDown()
     */
    protected function tearDown(): void
    {
        unset($this->tca);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function processDatamapDoNothingIfNothingSelected()
    {
        $mappingRepository = $this
            ->getMockBuilder(MappingRepository::class)
            ->disableOriginalConstructor()
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

        $dataHandlerMock = $this->createMock(DataHandler::class);
        $incomingFieldArray = [
            'tx_featureflag_flag' => '0',
            'tx_featureflag_behavior' => '0',
        ];
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', 4711, $dataHandlerMock);
    }

    /**
     * @test
     */
    public function processDatamapDoNothingIfNotInFeatureFlagContext()
    {
        $mappingRepository = $this
            ->getMockBuilder(MappingRepository::class)
            ->disableOriginalConstructor()
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

        $dataHandlerMock = $this->createMock(DataHandler::class);
        $incomingFieldArray = ['hidden' => '0'];
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', 4711, $dataHandlerMock);
    }

    /**
     * @test
     */
    public function processDatamapRemoveMappingIfNothingSelectedAndMappingExists()
    {
        $mapping = $this->createMock(Mapping::class);
        $mappingRepository = $this
            ->getMockBuilder(MappingRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneByForeignTableNameAndUid', 'remove', 'update'])
            ->getMock();
        $mappingRepository
            ->expects($this->once())
            ->method('findOneByForeignTableNameAndUid')
            ->willReturn($mapping);
        $mappingRepository->expects($this->once())->method('remove');
        $mappingRepository->expects($this->once())->method('update');
        $this->tca->expects($this->any())->method('getMappingRepository')->willReturn($mappingRepository);

        $dataHandlerMock = $this->createMock(DataHandler::class);
        $incomingFieldArray = [
            'tx_featureflag_flag' => '0',
            'tx_featureflag_behavior' => '0',
        ];
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', 4711, $dataHandlerMock);
    }

    /**
     * @test
     */
    public function processDatamapCreateNewMappingIfFeatureFlagGivenAndNoMappingPreviouslyCreated()
    {
        $featureFlag = $this
            ->getMockBuilder(FeatureFlag::class)
            ->setMethods(['getUid'])
            ->getMock();
        $featureFlag->expects($this->any())->method('getUid')->willReturn(4711);

        $mappingRepository = $this
            ->getMockBuilder(MappingRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneByForeignTableNameAndUid', 'add'])
            ->getMock();
        $mappingRepository
            ->expects($this->once())
            ->method('findOneByForeignTableNameAndUid')
            ->willReturn(null);
        $mappingRepository->expects($this->once())->method('add');

        $this->tca->expects($this->any())->method('getMappingRepository')->willReturn($mappingRepository);
        $this->tca->expects($this->any())->method('getFeatureFlagByUid')->willReturn($featureFlag);

        $dataHandlerMock = $this->createMock(DataHandler::class);
        $dataHandlerMock->expects($this->once())->method('getPID')->willReturn(678);
        $incomingFieldArray = [
            'tx_featureflag_flag' => '4711',
            'tx_featureflag_behavior' => '0',
        ];
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', 123, $dataHandlerMock);
    }

    /**
     * @test
     */
    public function processCmdmapCommandIsNotDelete()
    {
        $this->tca->expects($this->never())->method('getMappingRepository');
        $this->tca->processCmdmap_postProcess('not_delete', 'my_table', 4711);
    }

    /**
     * @test
     */
    public function processCmdmappostIsDelete()
    {
        $mappingRepository = $this
            ->getMockBuilder(MappingRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAllByForeignTableNameAndUid', 'remove'])
            ->getMock();
        $mappingRepository
            ->expects($this->once())
            ->method('findAllByForeignTableNameAndUid')
            ->willReturn($this->getListOfMappings());
        $mappingRepository->expects($this->exactly(2))->method('remove');
        $this->tca->expects($this->any())->method('getMappingRepository')->willReturn($mappingRepository);

        $this->tca->processCmdmap_postProcess('delete', 'my_table', 4711);
    }

    /**
     * @return array
     */
    protected function getListOfMappings()
    {
        $mapping1 = $this->createMock(Mapping::class);
        $mapping2 = $this->createMock(Mapping::class);
        $mapping3 = $this->createMock('stdClass');

        return [$mapping1, $mapping2, $mapping3];
    }

    /**
     * @return array
     */
    protected function getListOfFeatureFlags()
    {
        $featureFlag1 = $this
            ->getMockBuilder(FeatureFlag::class)
            ->setMethods(['getUid', 'getDescription'])
            ->getMock();
        $featureFlag1->expects($this->any())->method('getUid')->willReturn(111);
        $featureFlag1->expects($this->any())->method('getDescription')->willReturn('flag 1');
        $featureFlag2 = $this
            ->getMockBuilder(FeatureFlag::class)
            ->setMethods(['getUid', 'getDescription'])
            ->getMock();
        $featureFlag2->expects($this->any())->method('getUid')->willReturn(4711);
        $featureFlag2->expects($this->any())->method('getDescription')->willReturn('flag 2');
        $featureFlag3 = $this
            ->getMockBuilder(FeatureFlag::class)
            ->setMethods(['getUid', 'getDescription'])
            ->getMock();
        $featureFlag3->expects($this->any())->method('getDescription')->willReturn('flag 3');
        $featureFlag3->expects($this->any())->method('getUid')->willReturn(222);

        return [$featureFlag1, $featureFlag2, $featureFlag3];
    }
}
