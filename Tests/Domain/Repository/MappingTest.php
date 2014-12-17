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
class Tx_FeatureFlag_Domain_Repository_MappingTest extends Tx_FeatureFlag_Tests_BaseTest
{
    /**
     * @test
     */
    public function findOneByForeignTableNameAndUid()
    {
        $query = $this->getMock('Tx_Extbase_Persistence_Query', array('execute', 'matching', 'logicalAnd', 'equals'));
        $result = $this->getMock('Tx_Extbase_Persistence_QueryResult', array('getFirst'), array($query), '', true);
        $result->expects($this->once())->method('getFirst');
        $query->expects($this->once())->method('execute')->will($this->returnValue($result));
        $query->expects($this->once())->method('matching');
        $query->expects($this->once())->method('logicalAnd');
        $query->expects($this->at(0))->method('equals')->with(
            $this->equalTo('foreign_table_uid'),
            $this->equalTo(4711)
        );
        $query->expects($this->at(1))->method('equals')->with(
            $this->equalTo('foreign_table_name'),
            $this->equalTo('pages')
        );
        $repository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('createQuery'));
        $repository->expects($this->once())->method('createQuery')->will($this->returnValue($query));
        /** @var Tx_FeatureFlag_Domain_Repository_Mapping $repository */
        $repository->findOneByForeignTableNameAndUid(4711, 'pages');
    }

    /**
     * @test
     */
    public function findAllByForeignTableNameAndUid()
    {
        $query = $this->getMock('Tx_Extbase_Persistence_Query', array('execute', 'matching', 'logicalAnd', 'equals'));
        $result = $this->getMock('Tx_Extbase_Persistence_QueryResult', array('getFirst'), array($query), '', true);
        $result->expects($this->never())->method('getFirst');
        $query->expects($this->once())->method('execute')->will($this->returnValue($result));
        $query->expects($this->once())->method('matching');
        $query->expects($this->once())->method('logicalAnd');
        $query->expects($this->at(0))->method('equals')->with(
            $this->equalTo('foreign_table_uid'),
            $this->equalTo(4711)
        );
        $query->expects($this->at(1))->method('equals')->with(
            $this->equalTo('foreign_table_name'),
            $this->equalTo('pages')
        );
        $repository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('createQuery'));
        $repository->expects($this->once())->method('createQuery')->will($this->returnValue($query));
        /** @var Tx_FeatureFlag_Domain_Repository_Mapping $repository */
        $repository->findAllByForeignTableNameAndUid(4711, 'pages');
    }
}