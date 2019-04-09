<?php

namespace Aoe\FeatureFlag\Tests\Functional\Domain\Repository;

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

use Aoe\FeatureFlag\Domain\Model\FeatureFlag;
use Aoe\FeatureFlag\Domain\Repository\FeatureFlag as FeatureFlagRepository;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @package FeatureFlag
 * @subpackage Tests_Domain_Repository
 */
class FeatureFlagTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/feature_flag'];

    /**
     * @var FeatureFlagRepository
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
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);
        $this->featureFlagRepository = $this->objectManager->get(FeatureFlagRepository::class);
    }

    /**
     * @test
     */
    public function shouldGetFeatureFlagByFlagName()
    {
        $this->importDataSet(dirname(__FILE__) . '/fixtures/FeatureFlagTest.shouldGetFeatureFlagByFlagName.xml');
        $flag = $this->featureFlagRepository->findByFlag('my_test_feature_flag');
        $this->assertInstanceOf(FeatureFlag::class, $flag);
    }

    /**
     * @param integer $id
     * @return array
     */
    private function getContentElement($id)
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable('tt_content');
        return $queryBuilder
            ->select('uid,hidden')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
            )
            ->execute();
    }
}
