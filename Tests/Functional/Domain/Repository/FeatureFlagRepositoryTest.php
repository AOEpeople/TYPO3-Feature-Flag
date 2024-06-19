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
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FeatureFlagRepositoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/feature_flag'];

    /**
     * @var FeatureFlagRepository
     */
    protected $featureFlagRepository;

    /**
     * Set up testing framework
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->featureFlagRepository = GeneralUtility::makeInstance(FeatureFlagRepository::class);
    }

    public function testShouldGetFeatureFlagByFlagName(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/FeatureFlagTest.shouldGetFeatureFlagByFlagName.csv');
        $flag = $this->featureFlagRepository->findByFlag('my_test_feature_flag');
        $this->assertInstanceOf(FeatureFlag::class, $flag);
    }

    public function testShouldHideElementForBehaviorHideAndEnabledFeatureFlag(): void
    {
        $this->importCSVDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagTest.shouldHideElementForBehaviorHideAndEnabledFeatureFlag.csv'
        );
        $featureFlag = new FeatureFlagData();
        $instance = new FeatureFlagRepository($featureFlag);

        $instance->updateFeatureFlagStatusForTable('tt_content');

        $contentElements = $this->getElementsData('tt_content', 4712);
        $this->assertSame(1, $contentElements[0]['hidden']);
    }

    public function testShouldHideElementForBehaviorShowAndDisabledFeatureFlag(): void
    {
        $this->importCSVDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagTest.shouldHideElementForBehaviorShowAndDisabledFeatureFlag.csv'
        );
        $featureFlag = new FeatureFlagData();
        $instance = new FeatureFlagRepository($featureFlag);

        $instance->updateFeatureFlagStatusForTable('tt_content');

        $contentElements = $this->getElementsData('tt_content', 4712);
        $this->assertSame(1, $contentElements[0]['hidden']);
    }

    public function testShouldShowElementForBehaviorShowAndEnabledFeatureFlag(): void
    {
        $this->importCSVDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagTest.shouldShowElementForBehaviorShowAndEnabledFeatureFlag.csv'
        );
        $featureFlag = new FeatureFlagData();
        $instance = new FeatureFlagRepository($featureFlag);

        $instance->updateFeatureFlagStatusForTable('tt_content');

        $contentElements = $this->getElementsData('tt_content', 4712);
        $this->assertSame(0, $contentElements[0]['hidden']);
    }

    public function testShouldShowElementForBehaviorHideAndDisabledFeatureFlag(): void
    {
        $this->importCSVDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagTest.shouldShowElementForBehaviorShowAndEnabledFeatureFlag.csv'
        );
        $featureFlag = new FeatureFlagData();
        $instance = new FeatureFlagRepository($featureFlag);

        $instance->updateFeatureFlagStatusForTable('tt_content');

        $contentElements = $this->getElementsData('tt_content', 4712);
        $this->assertSame(0, $contentElements[0]['hidden']);
    }


    public function getElementsData(string $table, int $uid): array
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $queryBuilder->getRestrictions()
            ->removeAll();

        $query = $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()
                    ->eq(
                        'uid',
                        $queryBuilder->createNamedParameter(
                            $uid,
                            Connection::PARAM_INT
                        )
                    )
            );

        return $query->executeQuery()
            ->fetchAllAssociative();
    }
}
