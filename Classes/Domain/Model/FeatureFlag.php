<?php
/**
 * Feature Flag
 * @package FeatureFlag
 */
class Tx_FeatureFlag_Domain_Model_FeatureFlag extends Tx_Extbase_DomainObject_AbstractEntity
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $flag;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * @return string
     */
    public function getFlag()
    {
        return $this->flag;
    }
}