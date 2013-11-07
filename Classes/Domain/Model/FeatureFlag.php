<?php
/**
 * Feature Flag
 * @package FeatureFlag
 */
class Tx_FeatureFlag_Domain_Model_FeatureFlag
{
    /**
     * @var string
     */
    private $name;

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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}