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
class Tx_FeatureFlag_Tests_Unit_Domain_Model_FeatureFlagTest extends Tx_FeatureFlag_Tests_Unit_BaseTest
{
    /**
     * @var Tx_FeatureFlag_Domain_Model_FeatureFlag
     */
    private $featureFlag;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->featureFlag = new Tx_FeatureFlag_Domain_Model_FeatureFlag();
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->featureFlag = null;
    }

    /**
     * @test
     */
    public function checkProperties()
    {
        $this->featureFlag->setDescription('This is a test description');
        $this->featureFlag->setEnabled(true);
        $this->featureFlag->setFlag('my_new_feature_flag');
        $this->assertTrue($this->featureFlag->isEnabled());
        $this->assertEquals($this->featureFlag->getDescription(), 'This is a test description');
        $this->assertEquals($this->featureFlag->getFlag(), 'my_new_feature_flag');
    }
}
