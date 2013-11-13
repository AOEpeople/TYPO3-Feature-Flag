<?php

/**
 * Tx_FeatureFlag_Domain_Repository_Mapping test case.
 */
class Tx_FeatureFlag_Domain_Repository_MappingTest extends Tx_Extbase_Tests_Unit_BaseTestCase
{
    /**
     * @test
     */
    public function findByForeignTableAndId()
    {
        $query = $this->getMock('Tx_Extbase_Persistence_Query', array('execute', 'matching', 'logicalAnd', 'equals'));
        $result = $this->getMock('Tx_Extbase_Persistence_QueryResult', array('getFirst'), array($query), '', true);
        $result->expects($this->never())->method('getFirst');
        $query->expects($this->once())->method('execute')->will($this->returnValue($result));
        $query->expects($this->once())->method('matching');
        $query->expects($this->once())->method('logicalAnd');
        $query->expects($this->at(0))->method('equals')->with($this->equalTo('foreign_table_uid'), $this->equalTo(4711));
        $query->expects($this->at(1))->method('equals')->with($this->equalTo('foreign_table_name'), $this->equalTo('pages'));
        $repository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('createQuery'));
        $repository->expects($this->once())->method('createQuery')->will($this->returnValue($query));
        /** @var Tx_FeatureFlag_Domain_Repository_Mapping $repository */
        $repository->findByForeignTableNameAndUid(4711, 'pages');
    }

    /**
     * @test
     */
    public function findByForeignTableNameUidAndColumnName()
    {
        $query = $this->getMock('Tx_Extbase_Persistence_Query', array('execute', 'matching', 'logicalAnd', 'equals'));
        $result = $this->getMock('Tx_Extbase_Persistence_QueryResult', array('getFirst'), array($query), '', true);
        $result->expects($this->once())->method('getFirst');
        $query->expects($this->once())->method('execute')->will($this->returnValue($result));
        $query->expects($this->once())->method('matching');
        $query->expects($this->once())->method('logicalAnd');
        $query->expects($this->at(0))->method('equals')->with($this->equalTo('foreign_table_uid'), $this->equalTo(4711));
        $query->expects($this->at(1))->method('equals')->with($this->equalTo('foreign_table_name'), $this->equalTo('pages'));
        $query->expects($this->at(2))->method('equals')->with($this->equalTo('foreign_table_column'), $this->equalTo('title'));
        $repository = $this->getMock('Tx_FeatureFlag_Domain_Repository_Mapping', array('createQuery'));
        $repository->expects($this->once())->method('createQuery')->will($this->returnValue($query));
        /** @var Tx_FeatureFlag_Domain_Repository_Mapping $repository */
        $repository->findByForeignTableNameUidAndColumnName(4711, 'pages', 'title');
    }
}