<?php
namespace Aoe\FeatureFlag\Tests\Functional\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 AOE GmbH <dev@aoe.com>
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
use Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository;
use Aoe\FeatureFlag\System\Db\FeatureFlagData;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class FeatureFlagRepositoryTest extends FunctionalTestCase
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
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Set up testing framework
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->featureFlagRepository = $this->objectManager->get(FeatureFlagRepository::class);
    }

    /**
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::findByFlag()
     * @test
     */
    public function shouldGetFeatureFlagByFlagName()
    {
        $this->importDataSet(__DIR__ . '/fixtures/FeatureFlagTest.shouldGetFeatureFlagByFlagName.xml');
        $flag = $this->featureFlagRepository->findByFlag('my_test_feature_flag');
        $this->assertInstanceOf(FeatureFlag::class, $flag);
    }

    /**
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::hideEntries()
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::showEntries()
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::getUpdateEntriesUids()
     *
     * @test
     */
    public function shouldHideElementForBehaviorHideAndEnabledFeatureFlag()
    {
        $this->importDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagTest.shouldHideElementForBehaviorHideAndEnabledFeatureFlag.xml'
        );

        $featureFlag = new FeatureFlagData();
        $instance = new FeatureFlagRepository($featureFlag);

        $instance->updateFeatureFlagStatusForTable('tt_content');

        $contentElements = $this->getElementsData('tt_content', 4712);

        $this->assertEquals(1, $contentElements[0]['hidden']);
    }

    /**
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::hideEntries()
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::showEntries()
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::getUpdateEntriesUids()
     *
     * @test
     */
    public function shouldHideElementForBehaviorShowAndDisabledFeatureFlag()
    {
        $this->importDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagTest.shouldHideElementForBehaviorShowAndDisabledFeatureFlag.xml'
        );

        $featureFlag = new FeatureFlagData();
        $instance = new FeatureFlagRepository($featureFlag);

        $instance->updateFeatureFlagStatusForTable('tt_content');

        $contentElements = $this->getElementsData('tt_content', 4712);

        $this->assertEquals(1, $contentElements[0]['hidden']);
    }

    /**
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::hideEntries()
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::showEntries()
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::getUpdateEntriesUids()
     *
     * @test
     */
    public function shouldShowElementForBehaviorShowAndEnabledFeatureFlag()
    {
        $this->importDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagTest.shouldShowElementForBehaviorShowAndEnabledFeatureFlag.xml'
        );

        $featureFlag = new FeatureFlagData();
        $instance = new FeatureFlagRepository($featureFlag);

        $instance->updateFeatureFlagStatusForTable('tt_content');

        $contentElements = $this->getElementsData('tt_content', 4712);

        $this->assertEquals(0, $contentElements[0]['hidden']);
    }

    /**
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::hideEntries()
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::showEntries()
     * @covers \Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository::getUpdateEntriesUids()
     *
     * @test
     */
    public function shouldShowElementForBehaviorHideAndDisabledFeatureFlag()
    {
        $this->importDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagTest.shouldShowElementForBehaviorShowAndEnabledFeatureFlag.xml'
        );

        $featureFlag = new FeatureFlagData();
        $instance = new FeatureFlagRepository($featureFlag);

        $instance->updateFeatureFlagStatusForTable('tt_content');
        $contentElements = $this->getElementsData('tt_content', 4712);

        $this->assertEquals(0, $contentElements[0]['hidden']);
    }

    /**
     * Helper function for testing return
     *
     * @param string $table
     * @param integer $uid
     *
     * @return array
     */
    public function getElementsData($table, $uid)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $queryBuilder->getRestrictions()->removeAll();

        $query = $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter(
                        $uid,
                        Connection::PARAM_INT
                    )
                )

            );

        return $query->execute()->fetchAllAssociative();
    }
}
