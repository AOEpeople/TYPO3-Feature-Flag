<?php
namespace Aoe\FeatureFlag\Tests\Unit\Domain\Model;

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
use Aoe\FeatureFlag\Domain\Model\Mapping;
use Aoe\FeatureFlag\Tests\Unit\BaseTest;

class MappingTest extends BaseTest
{
    /**
     * @var Mapping
     */
    private $mapping;

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

    /**
     * @test
     */
    public function tstamp()
    {
        $this->mapping->setTstamp(2183466346);
        self::assertEquals($this->mapping->getTstamp(), 2183466346);
    }

    /**
     * @test
     */
    public function crdate()
    {
        $this->mapping->setCrdate(2183466347);
        self::assertEquals($this->mapping->getCrdate(), 2183466347);
    }

    /**
     * @test
     */
    public function featureFlag()
    {
        $featureFlag = $this->getMockBuilder(FeatureFlag::class)->setMethods(['getFlag'])->getMock();
        $featureFlag->method('getFlag')->willReturn('my_awesome_feature_flag');
        $this->mapping->setFeatureFlag($featureFlag);
        self::assertEquals($this->mapping->getFeatureFlag()->getFlag(), 'my_awesome_feature_flag');
    }

    /**
     * @test
     */
    public function foreignTableColumn()
    {
        $this->mapping->setForeignTableColumn('my_foreign_column');
        self::assertEquals($this->mapping->getForeignTableColumn(), 'my_foreign_column');
    }

    /**
     * @test
     */
    public function foreignTableName()
    {
        $this->mapping->setForeignTableName('my_foreign_table');
        self::assertEquals($this->mapping->getForeignTableName(), 'my_foreign_table');
    }

    /**
     * @test
     */
    public function foreignTableUid()
    {
        $this->mapping->setForeignTableUid(4711);
        self::assertEquals($this->mapping->getForeignTableUid(), 4711);
    }

    /**
     * @test
     */
    public function behavior()
    {
        $this->mapping->setBehavior(1);
        self::assertEquals($this->mapping->getBehavior(), 1);
    }
}
