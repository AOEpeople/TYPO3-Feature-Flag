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
class Tx_FeatureFlag_Tests_Functional_Domain_Repository_MappingTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/feature_flag'];

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
        parent::setUp();
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            'TYPO3\CMS\Extbase\Object\ObjectManager'
        );
        $this->mappingRepository = $this->objectManager->get('Tx_FeatureFlag_Domain_Repository_Mapping');
    }

    /**
     * @test
     */
    public function findOneByForeignTableNameAndUid()
    {
        $this->importDataSet(
            dirname(__FILE__) .
            '/fixtures/MappingTest.shouldHideElementForBehaviorHideAndEnabledFeatureFlag.xml'
        );

        $mapping = $this->mappingRepository->findOneByForeignTableNameAndUid(4712, 'tt_content');

        $this->assertInstanceOf('Tx_FeatureFlag_Domain_Model_Mapping', $mapping);
    }

    /**
     * @test
     */
    public function findAllByForeignTableNameAndUid()
    {
        $this->importDataSet(dirname(__FILE__) . '/fixtures/MappingTest.findAllByForeignTableNameAndUid.xml');

        $mapping = $this->mappingRepository->findAllByForeignTableNameAndUid(4711, 'tt_content');

        $this->assertCount(2, $mapping);
    }

    /**
     * @test
     */
    public function shouldGetHashedMappings()
    {
        $this->importDataSet(dirname(__FILE__) . '/fixtures/MappingTest.shouldGetHashedMappings.xml');

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
