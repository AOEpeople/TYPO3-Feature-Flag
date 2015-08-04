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
class Tx_FeatureFlag_Tests_Unit_Domain_Repository_MappingTest extends Tx_FeatureFlag_Tests_Unit_BaseTest
{
    /**
     * @var Tx_Phpunit_Framework
     */
    protected $testingFramework;

    /**
     * @var Tx_FeatureFlag_Domain_Repository_Mapping
     */
    protected $mappingRepository;

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
        $this->mappingRepository = $this->objectManager->get('Tx_FeatureFlag_Domain_Repository_Mapping');
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
    public function findOneByForeignTableNameAndUid()
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
            'behavior' => Tx_FeatureFlag_Service::BEHAVIOR_HIDE,
        ));

        $mapping = $this->mappingRepository->findOneByForeignTableNameAndUid($contentElementId, 'tt_content');

        $this->assertInstanceOf('Tx_FeatureFlag_Domain_Model_Mapping', $mapping);
    }

    /**
     * @test
     */
    public function findAllByForeignTableNameAndUid()
    {
        $this->testingFramework->createRecord('tx_featureflag_domain_model_mapping', array(
            'feature_flag' => 4711,
            'foreign_table_uid' => 4711,
            'foreign_table_name' => 'tt_content',
            'behavior' => Tx_FeatureFlag_Service::BEHAVIOR_HIDE,
        ));

        $this->testingFramework->createRecord('tx_featureflag_domain_model_mapping', array(
            'feature_flag' => 4712,
            'foreign_table_uid' => 4711,
            'foreign_table_name' => 'tt_content',
            'behavior' => Tx_FeatureFlag_Service::BEHAVIOR_HIDE,
        ));

        $mapping = $this->mappingRepository->findAllByForeignTableNameAndUid(4711, 'tt_content');

        $this->assertCount(2, $mapping);
    }

    /**
     * @test
     */
    public function shouldGetHashedMappings()
    {
        $this->testingFramework->createRecord('tx_featureflag_domain_model_mapping', array(
            'feature_flag' => 4711,
            'foreign_table_uid' => 4711,
            'foreign_table_name' => 'tt_content',
            'behavior' => Tx_FeatureFlag_Service::BEHAVIOR_HIDE,
        ));

        $this->testingFramework->createRecord('tx_featureflag_domain_model_mapping', array(
            'feature_flag' => 4712,
            'foreign_table_uid' => 4712,
            'foreign_table_name' => 'tt_content',
            'behavior' => Tx_FeatureFlag_Service::BEHAVIOR_HIDE,
        ));

        $hashedMappings = $this->mappingRepository->getHashedMappings();

        $this->assertEquals(
            '35d83e54054892288a31e71e40d8394e76032697',
            $hashedMappings['35d83e54054892288a31e71e40d8394e76032697']
        );
        $this->assertEquals(
            '39ecd17e510c064c9ea06162aaf58753b071177d',
            $hashedMappings['39ecd17e510c064c9ea06162aaf58753b071177d']
        );
    }
}
