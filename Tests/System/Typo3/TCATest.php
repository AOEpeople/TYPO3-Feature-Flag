<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 AOE GmbH <dev@aoemedia.de>
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
 * @author Kevin Schu <kevin.schu@aoemedia.de>
 * @author Matthias Gutjahr <matthias.gutjahr@aoemedia.de>
 */
class Tx_FeatureFlag_System_Typo3_TCATest extends Tx_Extbase_Tests_Unit_BaseTestCase
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
        $this->tca = $this->getMock('Tx_FeatureFlag_System_Typo3_TCA', array('getMappingRepository', 'getFeatureFlagRepository', 'getFeatureFlagByUid'));
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
        $featureFlag->_setProperty('uid', '4711');
        $mapping = $this->getMock('Tx_FeatureFlag_Domain_Model_Mapping', array('getFeatureFlag'));
        $mapping->expects($this->any())->method('getFeatureFlag')->will($this->returnValue($featureFlag));
        $mappingRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('findByForeignTableNameUidAndColumnName'));
        $mappingRepository->expects($this->once())->method('findByForeignTableNameUidAndColumnName')->will($this->returnValue($mapping));
        $featureFlagRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_FeatureFlag', array('findAll'));
        $featureFlagRepository->expects($this->once())->method('findAll')->will($this->returnValue($this->getListOfFeatureFlags()));
        $this->tca->expects($this->once())->method('getMappingRepository')->will($this->returnValue($mappingRepository));
        $this->tca->expects($this->once())->method('getFeatureFlagRepository')->will($this->returnValue($featureFlagRepository));

        $PA = array();
        $PA['row'] = array();
        $PA['row']['uid'] = '111';
        $PA['table'] = 'pages';
        $PA['field'] = 'tx_featureflag_hide';
        $PA['itemFormElID'] = 'itemFormElID';
        $PA['itemFormElName'] = 'itemFormElName';

        $content = $this->tca->renderSelect($PA, $this->getMock('t3lib_TCEforms'));

        $this->assertContains('<option value="0"></option>', $content);
        $this->assertContains('<option value="111">flag 1</option>', $content);
        $this->assertContains('<option value="4711" selected>flag 2</option>', $content);
        $this->assertContains('<option value="222">flag 3</option>', $content);
    }

    /**
     * @test
     */
    public function processDatamapDoNothingIfNothingSelected()
    {
        $mappingRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('findByForeignTableNameUidAndColumnName', 'add', 'remove', 'update'));
        $mappingRepository->expects($this->exactly(2))->method('findByForeignTableNameUidAndColumnName')->will($this->returnValue(null));
        $mappingRepository->expects($this->never())->method('add');
        $mappingRepository->expects($this->never())->method('remove');
        $mappingRepository->expects($this->never())->method('update');
        $this->tca->expects($this->exactly(2))->method('getMappingRepository')->will($this->returnValue($mappingRepository));
        $this->tca->expects($this->never())->method('getFeatureFlagByUid');

        $tceMainMock = $this->getMock('t3lib_TCEmain');
        $incomingFieldArray = array(
            'tx_featureflag_hide' => '0',
            'tx_featureflag_show' => '0',
        );
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', '4711', $tceMainMock);
    }

    /**
     * @test
     */
    public function processDatamapRemoveMappingIfNothingSelectedAndMappingExists()
    {
        $mapping = $this->getMock('Tx_FeatureFlag_Domain_Model_Mapping');
        $mappingRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('findByForeignTableNameUidAndColumnName', 'remove', 'update'));
        $mappingRepository->expects($this->exactly(2))->method('findByForeignTableNameUidAndColumnName')->will($this->returnValue($mapping));
        $mappingRepository->expects($this->exactly(2))->method('remove');
        $mappingRepository->expects($this->exactly(2))->method('update');
        $this->tca->expects($this->any())->method('getMappingRepository')->will($this->returnValue($mappingRepository));

        $tceMainMock = $this->getMock('t3lib_TCEmain');
        $incomingFieldArray = array(
            'tx_featureflag_hide' => '0',
            'tx_featureflag_show' => '0',
        );
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', '4711', $tceMainMock);
    }

    /**
     * @test
     */
    public function processDatamapRemoveMappingIfHideSelectedAndMappingExists()
    {
        $featureFlag = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getUid'));
        $featureFlag->_setProperty('uid', '4712');
        $expectedFeatureFlag = clone $featureFlag;
        $mapping = $this->getMock('Tx_FeatureFlag_Domain_Model_Mapping', array('setFeatureFlag'));
        $mapping->expects($this->once())->method('setFeatureFlag')->with($this->equalTo($expectedFeatureFlag));
        $mappingRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('findByForeignTableNameUidAndColumnName', 'remove', 'update'));
        $mappingRepository->expects($this->exactly(2))->method('findByForeignTableNameUidAndColumnName')->will($this->returnValue($mapping));
        $mappingRepository->expects($this->once())->method('remove');
        $mappingRepository->expects($this->exactly(2))->method('update');
        $this->tca->expects($this->any())->method('getMappingRepository')->will($this->returnValue($mappingRepository));
        $this->tca->expects($this->any())->method('getFeatureFlagByUid')->will($this->returnValue($featureFlag));

        $tceMainMock = $this->getMock('t3lib_TCEmain');
        $incomingFieldArray = array(
            'tx_featureflag_hide' => '4711',
            'tx_featureflag_show' => '0',
        );
        $this->tca->processDatamap_preProcessFieldArray($incomingFieldArray, 'my_table', 123, $tceMainMock);
    }

    /**
     * @test
     */
    public function processDatamapCreateNewMappingIfFeatureFlagGivenAndNoMappingPreviouslyCreated()
    {
        $featureFlag = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getUid'));
        $featureFlag->_setProperty('uid', 4711);

        $mappingRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('findByForeignTableNameUidAndColumnName', 'add'));
        $mappingRepository->expects($this->exactly(2))->method('findByForeignTableNameUidAndColumnName')->will($this->returnValue(null));
        $mappingRepository->expects($this->once())->method('add');

        $this->tca->expects($this->any())->method('getMappingRepository')->will($this->returnValue($mappingRepository));
        $this->tca->expects($this->any())->method('getFeatureFlagByUid')->will($this->returnValue($featureFlag));

        $tceMainMock = $this->getMock('t3lib_TCEmain');
        $incomingFieldArray = array(
            'tx_featureflag_hide' => '4711',
            'tx_featureflag_show' => '0',
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
        $mappingRepository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('findByForeignTableNameAndUid', 'remove'));
        $mappingRepository->expects($this->once())->method('findByForeignTableNameAndUid')->will($this->returnValue($this->getListOfMappings()));
        $mappingRepository->expects($this->exactly(2))->method('remove');
        $this->tca->expects($this->any())->method('getMappingRepository')->will($this->returnValue($mappingRepository));

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
        $featureFlag1->_setProperty('uid', 111);
        $featureFlag1->expects($this->any())->method('getDescription')->will($this->returnValue('flag 1'));
        $featureFlag2 = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getUid', 'getDescription'));
        $featureFlag2->_setProperty('uid', 4711);
        $featureFlag2->expects($this->any())->method('getDescription')->will($this->returnValue('flag 2'));
        $featureFlag3 = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getUid', 'getDescription'));
        $featureFlag3->expects($this->any())->method('getDescription')->will($this->returnValue('flag 3'));
        $featureFlag3->_setProperty('uid', 222);
        return array($featureFlag1, $featureFlag2, $featureFlag3);
    }
}