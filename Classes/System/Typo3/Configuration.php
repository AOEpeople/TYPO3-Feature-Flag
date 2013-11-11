<?php

/**
 * Class Tx_FeatureFlag_System_Typo3_Configuration
 */
class Tx_FeatureFlag_System_Typo3_Configuration
{
    /**
     * @var string
     */
    const CONF_TABLES = 'tables';

    /**
     * @var array
     */
    private $configuration = array();

    /**
     * Initialize configuration array
     */
    public function __construct()
    {
        $conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);
        if (is_array($conf)) {
            $this->configuration = $conf;
        }
    }

    /**
     * @return array
     */
    public function getTables()
    {
        return explode(',', $this->get(self::CONF_TABLES));
    }

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->configuration)) {
            return $this->configuration[$key];
        }
        throw new InvalidArgumentException('Configuration key "' . $key . '" does not exist.', 1384161387);
    }
}
