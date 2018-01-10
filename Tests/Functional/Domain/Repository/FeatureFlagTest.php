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

/**
 * @package FeatureFlag
 * @subpackage Tests_Domain_Repository
 */
class Tx_FeatureFlag_Tests_Functional_Domain_Repository_FeatureFlagTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/feature_flag'];

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
        parent::setUp();
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            'TYPO3\CMS\Extbase\Object\ObjectManager'
        );
        $this->featureFlagRepository = $this->objectManager->get('Tx_FeatureFlag_Domain_Repository_FeatureFlag');
    }

    /**
     * @test
     */
    public function shouldGetFeatureFlagByFlagName()
    {
        $this->importDataSet(dirname(__FILE__) . '/fixtures/FeatureFlagTest.shouldGetFeatureFlagByFlagName.xml');
        $flag = $this->featureFlagRepository->findByFlag('my_test_feature_flag');
        $this->assertInstanceOf('Tx_FeatureFlag_Domain_Model_FeatureFlag', $flag);
    }

    /**
     * @test
     */
    public function shouldHideElementForBehaviorHideAndEnabledFeatureFlag()
    {
        $this->importDataSet(
            dirname(__FILE__) .
            '/fixtures/FeatureFlagTest.shouldHideElementForBehaviorHideAndEnabledFeatureFlag.xml'
        );

        $this->featureFlagRepository->updateFeatureFlagStatusForTable('tt_content');

        $contentElement = $this->getContentElement(4712);

        $this->assertEquals('1', $contentElement['hidden']);
    }

    /**
     * @test
     */
    public function shouldHideElementForBehaviorShowAndDisabledFeatureFlag()
    {
        $this->importDataSet(
            dirname(__FILE__) .
            '/fixtures/FeatureFlagTest.shouldHideElementForBehaviorShowAndDisabledFeatureFlag.xml'
        );

        $this->featureFlagRepository->updateFeatureFlagStatusForTable('tt_content');

        $contentElement = $this->getContentElement(4712);

        $this->assertEquals('1', $contentElement['hidden']);
    }

    /**
     * @test
     */
    public function shouldShowElementForBehaviorShowAndEnabledFeatureFlag()
    {
        $this->importDataSet(
            dirname(__FILE__) .
            '/fixtures/FeatureFlagTest.shouldShowElementForBehaviorShowAndEnabledFeatureFlag.xml'
        );

        $this->featureFlagRepository->updateFeatureFlagStatusForTable('tt_content');

        $contentElement = $this->getContentElement(4712);

        $this->assertEquals('0', $contentElement['hidden']);
    }

    /**
     * @test
     */
    public function shouldShowElementForBehaviorHideAndDisabledFeatureFlag()
    {
        $this->importDataSet(
            dirname(__FILE__) .
            '/fixtures/FeatureFlagTest.shouldShowElementForBehaviorShowAndEnabledFeatureFlag.xml'
        );

        $this->featureFlagRepository->updateFeatureFlagStatusForTable('tt_content');

        $contentElement = $this->getContentElement(4712);

        $this->assertEquals('0', $contentElement['hidden']);
    }

    /**
     * @param integer $id
     * @return array
     */
    private function getContentElement($id)
    {
        return $this->getDatabaseConnection()->selectSingleRow('uid,hidden', 'tt_content', 'uid=' . $id);
    }
}
