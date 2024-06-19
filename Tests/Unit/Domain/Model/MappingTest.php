<?php

namespace Aoe\FeatureFlag\Tests\Unit\Domain\Model;

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

use Aoe\FeatureFlag\Domain\Model\FeatureFlag;
use Aoe\FeatureFlag\Domain\Model\Mapping;
use Aoe\FeatureFlag\Tests\Unit\BaseTestCase;

class MappingTest extends BaseTestCase
{
    private ?\Aoe\FeatureFlag\Domain\Model\Mapping $mapping = null;

    /**
     * (non-PHPdoc)
     * @see TestCase::setUp()
     */
    protected function setUp(): void
    {
        $this->mapping = new Mapping();
    }

    /**
     * (non-PHPdoc)
     * @see TestCase::tearDown()
     */
    protected function tearDown(): void
    {
        $this->mapping = null;
        parent::tearDown();
    }

    public function testTstamp(): void
    {
        $this->mapping->setTstamp(2183466346);
        $this->assertEquals($this->mapping->getTstamp(), 2183466346);
    }

    public function testCrdate(): void
    {
        $this->mapping->setCrdate(2183466347);
        $this->assertEquals($this->mapping->getCrdate(), 2183466347);
    }

    public function testFeatureFlag(): void
    {
        $featureFlag = $this->getMockBuilder(FeatureFlag::class)->onlyMethods(['getFlag'])->getMock();
        $featureFlag->method('getFlag')
            ->willReturn('my_awesome_feature_flag');
        $this->mapping->setFeatureFlag($featureFlag);
        $this->assertEquals($this->mapping->getFeatureFlag()->getFlag(), 'my_awesome_feature_flag');
    }

    public function testForeignTableColumn(): void
    {
        $this->mapping->setForeignTableColumn('my_foreign_column');
        $this->assertEquals($this->mapping->getForeignTableColumn(), 'my_foreign_column');
    }

    public function testForeignTableName(): void
    {
        $this->mapping->setForeignTableName('my_foreign_table');
        $this->assertEquals($this->mapping->getForeignTableName(), 'my_foreign_table');
    }

    public function testForeignTableUid(): void
    {
        $this->mapping->setForeignTableUid(4711);
        $this->assertEquals($this->mapping->getForeignTableUid(), 4711);
    }

    public function testBehavior(): void
    {
        $this->mapping->setBehavior(1);
        $this->assertSame($this->mapping->getBehavior(), 1);
    }
}
