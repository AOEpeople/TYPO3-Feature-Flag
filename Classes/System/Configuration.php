<?php
/**
 * Class Configuration
 * @package FeatureFlag
 */
class Tx_FeatureFlag_System_Configuration
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * Initialize configuration array from GLOBALS
     */
    public function __construct()
    {
        $this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);
    }

    /**
     * Returns configuration value by given key
     *
     * @param string $key
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getValueForKey($key)
    {
        if (false === isset($this->configuration[$key])) {
            throw new InvalidArgumentException('Key not found: "' . $key . '"', 1383821261);
        }
        return $this->configuration[$key];
    }
} 