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
 * @subpackage Tests_Domain_Repository
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_FeatureFlag_Domain_Repository_FeatureFlagTest extends Tx_FeatureFlag_Tests_BaseTest
{
    /**
     * @var Tx_Phpunit_Framework
     */
    protected $testingFramework;

    /**
     * @var Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    protected $featureFlagRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * Set up testing framework
     */
    public function setUp()
    {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            'TYPO3\CMS\Extbase\Object\ObjectManager'
        );
        $this->testingFramework = new Tx_Phpunit_Framework('tx_featureflag');
        $this->featureFlagRepository = $this->objectManager->get('Tx_FeatureFlag_Domain_Repository_FeatureFlag');
    }

    /**
     * Clean up testing framework
     */
    public function tearDown()
    {
        $this->testingFramework->cleanUp();
    }

    /**
     * @test
     */
    public function shouldGetFeatureFlagByFlagName()
    {
        $this->testingFramework->createRecord('tx_featureflag_domain_model_featureflag', array(
            'description' => 'lorem ipsum',
            'flag' => 'my_test_feature_flag',
            'enabled' => '0',
        ));
        $flag = $this->featureFlagRepository->findByFlag('my_test_feature_flag');
        $this->assertInstanceOf('Tx_FeatureFlag_Domain_Model_FeatureFlag', $flag);
    }

    /**
     * @test
     */
    public function shouldHideElementForBehaviorHideAndEnabledFeatureFlag()
    {
        $featureFlagId = $this->testingFramework->createRecord('tx_featureflag_domain_model_featureflag', array(
            'description' => 'lorem ipsum',
            'flag' => 'shouldHideElementForBehaviorHideAndEnabledFeatureFlag',
            'enabled' => '1',
        ));

        $contentElementId = $this->testingFramework->createContentElement(0, array('hidden' => 0));

        $this->testingFramework->createRecord('tx_featureflag_domain_model_mapping', array(
            'feature_flag' => $featureFlagId,
            'foreign_table_uid' => $contentElementId,
            'foreign_table_name' => 'tt_content',
            'behavior' => Tx_FeatureFlag_Service::BEHAVIOR_HIDE,
        ));

        $this->featureFlagRepository->updateFeatureFlagStatusForTable('tt_content');

        $contentElement = $this->getContentElement($contentElementId)->fetch_array();

        $this->assertEquals('1', $contentElement['hidden']);
    }

    /**
     * @test
     */
    public function shouldHideElementForBehaviorShowAndDisabledFeatureFlag()
    {
        $featureFlagId = $this->testingFramework->createRecord('tx_featureflag_domain_model_featureflag', array(
            'description' => 'lorem ipsum',
            'flag' => 'shouldHideElementForBehaviorHideAndEnabledFeatureFlag',
            'enabled' => '0',
        ));

        $contentElementId = $this->testingFramework->createContentElement(0, array('hidden' => 0));

        $this->testingFramework->createRecord('tx_featureflag_domain_model_mapping', array(
            'feature_flag' => $featureFlagId,
            'foreign_table_uid' => $contentElementId,
            'foreign_table_name' => 'tt_content',
            'behavior' => Tx_FeatureFlag_Service::BEHAVIOR_SHOW,
        ));

        $this->featureFlagRepository->updateFeatureFlagStatusForTable('tt_content');

        $contentElement = $this->getContentElement($contentElementId)->fetch_array();

        $this->assertEquals('1', $contentElement['hidden']);
    }

    /**
     * @test
     */
    public function shouldShowElementForBehaviorShowAndEnabledFeatureFlag()
    {
        $featureFlagId = $this->testingFramework->createRecord('tx_featureflag_domain_model_featureflag', array(
            'description' => 'lorem ipsum',
            'flag' => 'shouldHideElementForBehaviorHideAndEnabledFeatureFlag',
            'enabled' => '1',
        ));

        $contentElementId = $this->testingFramework->createContentElement(0, array('hidden' => 1));

        $this->testingFramework->createRecord('tx_featureflag_domain_model_mapping', array(
            'feature_flag' => $featureFlagId,
            'foreign_table_uid' => $contentElementId,
            'foreign_table_name' => 'tt_content',
            'behavior' => Tx_FeatureFlag_Service::BEHAVIOR_SHOW,
        ));

        $this->featureFlagRepository->updateFeatureFlagStatusForTable('tt_content');

        $contentElement = $this->getContentElement($contentElementId)->fetch_array();

        $this->assertEquals('0', $contentElement['hidden']);
    }

    /**
     * @test
     */
    public function shouldShowElementForBehaviorHideAndDisabledFeatureFlag()
    {
        $featureFlagId = $this->testingFramework->createRecord('tx_featureflag_domain_model_featureflag', array(
            'description' => 'lorem ipsum',
            'flag' => 'shouldHideElementForBehaviorHideAndEnabledFeatureFlag',
            'enabled' => '0',
        ));

        $contentElementId = $this->testingFramework->createContentElement(0, array('hidden' => 1));

        $this->testingFramework->createRecord('tx_featureflag_domain_model_mapping', array(
            'feature_flag' => $featureFlagId,
            'foreign_table_uid' => $contentElementId,
            'foreign_table_name' => 'tt_content',
            'behavior' => Tx_FeatureFlag_Service::BEHAVIOR_HIDE,
        ));

        $this->featureFlagRepository->updateFeatureFlagStatusForTable('tt_content');

        $contentElement = $this->getContentElement($contentElementId)->fetch_array();

        $this->assertEquals('0', $contentElement['hidden']);
    }

    /**
     * @param $id
     * @return mysqli_result
     * @throws Tx_Phpunit_Exception_Database
     */
    private function getContentElement($id)
    {
        return Tx_Phpunit_Service_Database::select('uid,hidden', 'tt_content', 'uid = ' . $id);
    }
}

