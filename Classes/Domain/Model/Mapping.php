<?php
/**
 * Feature Flag Mapping
 * @package FeatureFlag
 */
class Tx_FeatureFlag_Domain_Model_Mapping extends Tx_Extbase_DomainObject_AbstractEntity
{
    /**
     * @var string
     */
    protected $tstamp;

    /**
     * @var string
     */
    protected $crdate;

    /**
     * @var Tx_FeatureFlag_Domain_Model_FeatureFlag
     */
    protected $featureFlag;

    /**
     * @var int
     */
    protected $foreignTableUid;

    /**
     * @var string
     */
    protected $foreignTableName;

    /**
     * @var int
     */
    protected $foreignTableColumn;

    /**
     * @var boolean
     */
    protected $processed;

    /**
     * @param string $crdate
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * @return string
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * @param \Tx_FeatureFlag_Domain_Model_FeatureFlag $featureFlag
     */
    public function setFeatureFlag(Tx_FeatureFlag_Domain_Model_FeatureFlag $featureFlag)
    {
        $this->featureFlag = $featureFlag;
    }

    /**
     * @return \Tx_FeatureFlag_Domain_Model_FeatureFlag
     */
    public function getFeatureFlag()
    {
        return $this->featureFlag;
    }

    /**
     * @param int $foreignTableColumn
     */
    public function setForeignTableColumn($foreignTableColumn)
    {
        $this->foreignTableColumn = $foreignTableColumn;
    }

    /**
     * @return int
     */
    public function getForeignTableColumn()
    {
        return $this->foreignTableColumn;
    }

    /**
     * @param string $foreignTableName
     */
    public function setForeignTableName($foreignTableName)
    {
        $this->foreignTableName = $foreignTableName;
    }

    /**
     * @return string
     */
    public function getForeignTableName()
    {
        return $this->foreignTableName;
    }

    /**
     * @param int $foreignTableUid
     */
    public function setForeignTableUid($foreignTableUid)
    {
        $this->foreignTableUid = $foreignTableUid;
    }

    /**
     * @return int
     */
    public function getForeignTableUid()
    {
        return $this->foreignTableUid;
    }

    /**
     * @param string $tstamp
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * @return string
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * @param boolean $processed
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;
    }

    /**
     * @return boolean
     */
    public function getProcessed()
    {
        return $this->processed;
    }
}