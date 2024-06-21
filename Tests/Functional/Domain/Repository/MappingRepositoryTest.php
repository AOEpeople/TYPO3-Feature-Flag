<?php

namespace Aoe\FeatureFlag\Tests\Functional\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2024 AOE GmbH <dev@aoe.com>
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

use Aoe\FeatureFlag\Domain\Model\Mapping;
use Aoe\FeatureFlag\Domain\Repository\MappingRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class MappingRepositoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/feature_flag'];

    /**
     * @var MappingRepository
     */
    protected $mappingRepository;

    /**
     * Set up testing framework
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->mappingRepository = GeneralUtility::makeInstance(MappingRepository::class);
    }

    public function testFindOneByForeignTableNameAndUid(): void
    {
        $this->importCSVDataSet(
            __DIR__ .
            '/fixtures/MappingTest.shouldHideElementForBehaviorHideAndEnabledFeatureFlag.csv'
        );

        $mapping = $this->mappingRepository->findOneByForeignTableNameAndUid(4712, 'tt_content');

        $this->assertInstanceOf(Mapping::class, $mapping);
    }

    public function testFindAllByForeignTableNameAndUid(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/MappingTest.findAllByForeignTableNameAndUid.csv');

        $mapping = $this->mappingRepository->findAllByForeignTableNameAndUid(
            4711,
            'tt_content'
        );

        $this->assertCount(2, $mapping);
    }

    public function testShouldGetHashedMappings(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/MappingTest.shouldGetHashedMappings.csv');

        $hashedMappings = $this->mappingRepository->getHashedMappings();

        $this->assertSame('35d83e54054892288a31e71e40d8394e76032697', $hashedMappings['35d83e54054892288a31e71e40d8394e76032697']);
        $this->assertSame('39ecd17e510c064c9ea06162aaf58753b071177d', $hashedMappings['39ecd17e510c064c9ea06162aaf58753b071177d']);
    }
}
