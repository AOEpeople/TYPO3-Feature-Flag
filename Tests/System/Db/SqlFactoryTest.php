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
 * @subpackage Tests_System_Db
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_FeatureFlag_System_Db_SqlFactoryTest extends Tx_FeatureFlag_Tests_BaseTest
{
    /**
     * @var Tx_FeatureFlag_System_Db_SqlFactory
     */
    private $sqlFactory;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->sqlFactory = new Tx_FeatureFlag_System_Db_SqlFactory();
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        unset($this->sqlFactory);
    }

    /**
     * @test
     */
    public function canCreateSelectStatementForContentElements()
    {
        $expected = 'SELECT my_table.uid FROM my_table,tx_featureflag_domain_model_mapping,tx_featureflag_domain_model_featureflag WHERE tx_featureflag_domain_model_mapping.feature_flag=tx_featureflag_domain_model_featureflag.uid AND my_table.uid=tx_featureflag_domain_model_mapping.foreign_table_uid AND tx_featureflag_domain_model_featureflag.enabled=1 AND tx_featureflag_domain_model_featureflag.deleted=0 AND tx_featureflag_domain_model_featureflag.hidden=0 AND tx_featureflag_domain_model_mapping.foreign_table_name="my_table" AND tx_featureflag_domain_model_mapping.behavior=0';
        $actual = $this->sqlFactory->getSelectStatementForContentElements('my_table', '0', '1');
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canCreateUpdateStatementForVisibleContentElements()
    {
        $expected = 'UPDATE my_table SET hidden = 0 WHERE uid IN (1,2);';
        $actual = $this->sqlFactory->getUpdateStatementForContentElements('my_table', array(1,2), true);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canCreateUpdateStatementForInVisibleContentElements()
    {
        $expected = 'UPDATE my_table SET hidden = 1 WHERE uid IN (3,4);';
        $actual = $this->sqlFactory->getUpdateStatementForContentElements('my_table', array(3,4), false);
        $this->assertEquals($expected, $actual);
    }
}