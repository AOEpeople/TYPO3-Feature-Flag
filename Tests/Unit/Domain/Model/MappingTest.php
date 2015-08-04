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
 * @subpackage Tests_Domain_Model
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_FeatureFlag_Tests_Unit_Domain_Model_MappingTest extends Tx_FeatureFlag_Tests_Unit_BaseTest
{
    /**
     * @var Tx_FeatureFlag_Domain_Model_Mapping
     */
    private $mapping;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->mapping = new Tx_FeatureFlag_Domain_Model_Mapping();
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->mapping = null;
    }

    /**
     * @test
     */
    public function tstamp()
    {
        $this->mapping->setTstamp(2183466346);
        $this->assertEquals($this->mapping->getTstamp(), 2183466346);
    }

    /**
     * @test
     */
    public function crdate()
    {
        $this->mapping->setCrdate(2183466347);
        $this->assertEquals($this->mapping->getCrdate(), 2183466347);
    }

    /**
     * @test
     */
    public function featureFlag()
    {
        $featureFlag = $this->getMock('Tx_FeatureFlag_Domain_Model_FeatureFlag', array('getFlag'));
        $featureFlag->expects($this->any())->method('getFlag')->will($this->returnValue('my_awesome_feature_flag'));
        $this->mapping->setFeatureFlag($featureFlag);
        $this->assertEquals($this->mapping->getFeatureFlag()->getFlag(), 'my_awesome_feature_flag');
    }

    /**
     * @test
     */
    public function foreignTableColumn()
    {
        $this->mapping->setForeignTableColumn('my_foreign_column');
        $this->assertEquals($this->mapping->getForeignTableColumn(), 'my_foreign_column');
    }

    /**
     * @test
     */
    public function foreignTableName()
    {
        $this->mapping->setForeignTableName('my_foreign_table');
        $this->assertEquals($this->mapping->getForeignTableName(), 'my_foreign_table');
    }

    /**
     * @test
     */
    public function foreignTableUid()
    {
        $this->mapping->setForeignTableUid(4711);
        $this->assertEquals($this->mapping->getForeignTableUid(), 4711);
    }

    /**
     * @test
     */
    public function behavior()
    {
        $this->mapping->setBehavior(1);
        $this->assertEquals($this->mapping->getBehavior(), 1);
    }
}
