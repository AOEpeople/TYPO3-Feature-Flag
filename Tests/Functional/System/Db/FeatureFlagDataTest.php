<?php

namespace Aoe\FeatureFlag\Tests\Functional\System\Db;

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

use Aoe\FeatureFlag\System\Db\FeatureFlagData;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FeatureFlagDataTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/feature_flag'];

    public function testShouldGetContentElements(): void
    {
        $this->importDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagDataTest.xml'
        );

        $instance = new FeatureFlagData();

        $instance->getContentElements('tt_content', 0, 1);

        $contentElements = $this->getElementsData('tt_content', 4712);

        $this->assertSame(0, $contentElements[0]['hidden']);
    }

    public function testUpdateContentElements(): void
    {
        $this->importDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagDataTest.xml'
        );

        $instance = new FeatureFlagData();

        $instance->updateContentElements('tt_content', [4712], false);

        $contentElements = $this->getElementsData('tt_content', 4712);

        $this->assertSame(1, $contentElements[0]['hidden']);
    }

    public function testGetContentElementsPIDs(): void
    {
        $this->importDataSet(
            __DIR__ .
            '/fixtures/FeatureFlagDataTest.xml'
        );

        $instance = new FeatureFlagData();
        $returnedPID = $instance->getContentElementsPIDs('tx_featureflag_domain_model_featureflag', 4711);

        $this->assertSame('1001', $returnedPID);
    }

    /**
     * @return array
     */
    public function getElementsData(string $table, $uid)
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
