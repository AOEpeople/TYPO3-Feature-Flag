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
 * @subpackage Tests_System_Typo3
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_FeatureFlag_Tests_Unit_System_Typo3_TCATest extends Tx_FeatureFlag_Tests_Unit_BaseTest
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $tca;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->tca = $this->getMock(
            'Tx_FeatureFlag_System_Typo3_TCA',
            array('getMappingRepository', 'getFeatureFlagRepository', 'getFeatureFlagByUid', 'getPersistenceManager', 'getLanguageService')
        );
        $persistenceManager = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')
            ->disableOriginalConstructor()
            ->setMethods(array('persistAll'))
            ->getMock();

        $languageService = $this->getMockBuilder('TYPO3\\CMS\\Lang\\LanguageService')
            ->disableOriginalConstructor()
            ->setMethods(array('sL'))
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
    }

    /**
     * @test
     */
    public function selectRendersCorrectly()
    {
        $featureFlag = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getUid'));
        $featureFlag->expects($this->any())->method('getUid')->willReturn(4711);
        $mapping = $this->getMock('Tx_FeatureFlag_Domain_Model_Mapping', array('getFeatureFlag'));
        $mapping->expects($this->any())->method('getFeatureFlag')->willReturn($featureFlag);
        $mappingRepository = $this->getMock(
            'Tx_FeatureFlag_Domain_Repository_Mapping',
            array('findOneByForeignTableNameAndUid')
        );
        $mappingRepository->expects($this->once())->method('findOneByForeignTableNameAndUid')->willReturn($mapping);
        $featureFlagRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_FeatureFlag', array('findAll'));
        $featureFlagRepository->expects($this->once())->method('findAll')->willReturn($this->getListOfFeatureFlags());
        $this->tca->expects($this->once())->method('getMappingRepository')->willReturn($mappingRepository);
        $this->tca->expects($this->once())->method('getFeatureFlagRepository')->willReturn($featureFlagRepository);

        $PA = array();
        $PA['row'] = array();
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
        $mappingRepository = $this->getMock(
            'Tx_FeatureFlag_Domain_Repository_Mapping',
            array('findOneByForeignTableNameAndUid', 'add', 'remove', 'update')
        );
        $mappingRepository->expects($this->once())->method('findOneByForeignTableNameAndUid')->willReturn(null);
        $mappingRepository->expects($this->never())->method('add');
        $mappingRepository->expects($this->never())->method('remove');
        $mappingRepository->expects($this->never())->method('update');
        $this->tca->expects($this->once())->method('getMappingRepository')->willReturn($mappingRepository);
        $this->tca->expects($this->never())->method('getFeatureFlagByUid');

        $tceMainMock = $this->getMock('TYPO3\\CMS\\Core\\DataHandling\\DataHandler');
        $incomingFieldArray = array(
            'tx_featureflag_flag' => '0',
            'tx_featureflag_behavior' => '0',
        );
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', '4711', $tceMainMock);
    }

    /**
     * @test
     */
    public function processDatamapDoNothingIfNotInFeatureFlagContext()
    {
        $mappingRepository = $this->getMock(
            'Tx_FeatureFlag_Domain_Repository_Mapping',
            array('findOneByForeignTableNameAndUid', 'add', 'remove', 'update')
        );
        $mappingRepository->expects($this->never())->method('findOneByForeignTableNameAndUid')->willReturn(null);
        $mappingRepository->expects($this->never())->method('add');
        $mappingRepository->expects($this->never())->method('remove');
        $mappingRepository->expects($this->never())->method('update');
        $this->tca->expects($this->never())->method('getMappingRepository')->willReturn($mappingRepository);
        $this->tca->expects($this->never())->method('getFeatureFlagByUid');

        $tceMainMock = $this->getMock('TYPO3\\CMS\\Core\\DataHandling\\DataHandler');
        $incomingFieldArray = array(
            'hidden' => '0',
        );
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', '4711', $tceMainMock);
    }

    /**
     * @test
     */
    public function processDatamapRemoveMappingIfNothingSelectedAndMappingExists()
    {
        $mapping = $this->getMock('Tx_FeatureFlag_Domain_Model_Mapping');
        $mappingRepository = $this->getMock(
            'Tx_FeatureFlag_Domain_Repository_Mapping',
            array('findOneByForeignTableNameAndUid', 'remove', 'update')
        );
        $mappingRepository->expects($this->once())->method('findOneByForeignTableNameAndUid')->willReturn($mapping);
        $mappingRepository->expects($this->once())->method('remove');
        $mappingRepository->expects($this->once())->method('update');
        $this->tca->expects($this->any())->method('getMappingRepository')->willReturn($mappingRepository);

        $tceMainMock = $this->getMock('TYPO3\\CMS\\Core\\DataHandling\\DataHandler');
        $incomingFieldArray = array(
            'tx_featureflag_flag' => '0',
            'tx_featureflag_behavior' => '0',
        );
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', '4711', $tceMainMock);
    }

    /**
     * @test
     */
    public function processDatamapCreateNewMappingIfFeatureFlagGivenAndNoMappingPreviouslyCreated()
    {
        $featureFlag = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getUid'));
        $featureFlag->expects($this->any())->method('getUid')->willReturn(4711);

        $mappingRepository = $this->getMock(
            'Tx_FeatureFlag_Domain_Repository_Mapping',
            array('findOneByForeignTableNameAndUid', 'add')
        );
        $mappingRepository->expects($this->once())->method('findOneByForeignTableNameAndUid')->willReturn(null);
        $mappingRepository->expects($this->once())->method('add');

        $this->tca->expects($this->any())->method('getMappingRepository')->willReturn($mappingRepository);
        $this->tca->expects($this->any())->method('getFeatureFlagByUid')->willReturn($featureFlag);

        $tceMainMock = $this->getMock('TYPO3\\CMS\\Core\\DataHandling\\DataHandler');
        $incomingFieldArray = array(
            'tx_featureflag_flag' => '4711',
            'tx_featureflag_behavior' => '0',
        );
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
        $mappingRepository = $this->getMock(
            'Tx_FeatureFlag_Domain_Repository_Mapping',
            array('findAllByForeignTableNameAndUid', 'remove')
        );
        $mappingRepository->expects($this->once())->method('findAllByForeignTableNameAndUid')->willReturn($this->getListOfMappings());
        $mappingRepository->expects($this->exactly(2))->method('remove');
        $this->tca->expects($this->any())->method('getMappingRepository')->willReturn($mappingRepository);

        $this->tca->processCmdmap_postProcess('delete', 'my_table', '4711');
    }

    /**
     * @return array
     */
    protected function getListOfMappings()
    {
        $mapping1 = $this->getMock('Tx_FeatureFlag_Domain_Model_Mapping');
        $mapping2 = $this->getMock('Tx_FeatureFlag_Domain_Model_Mapping');
        $mapping3 = $this->getMock('stdClass');

        return array($mapping1, $mapping2, $mapping3);
    }

    /**
     * @return array
     */
    protected function getListOfFeatureFlags()
    {
        $featureFlag1 = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getUid', 'getDescription'));
        $featureFlag1->expects($this->any())->method('getUid')->willReturn(111);
        $featureFlag1->expects($this->any())->method('getDescription')->willReturn('flag 1');
        $featureFlag2 = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getUid', 'getDescription'));
        $featureFlag2->expects($this->any())->method('getUid')->willReturn(4711);
        $featureFlag2->expects($this->any())->method('getDescription')->willReturn('flag 2');
        $featureFlag3 = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getUid', 'getDescription'));
        $featureFlag3->expects($this->any())->method('getDescription')->willReturn('flag 3');
        $featureFlag3->expects($this->any())->method('getUid')->willReturn(222);

        return array($featureFlag1, $featureFlag2, $featureFlag3);
    }
}
