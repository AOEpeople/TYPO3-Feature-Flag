<?php

/**
 * @package FeatureFlag
 */
class Tx_FeatureFlag_Service
{
    /**
     * @var Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    private $featureFlagRepository;

    /**
     * @var array
     */
    private $cachedFlags = array();

    /**
     * @param Tx_FeatureFlag_Domain_Repository_FeatureFlag $featureFlagRepository
     */
    public function injectFeatureFlagRepository(Tx_FeatureFlag_Domain_Repository_FeatureFlag $featureFlagRepository)
    {
        $this->featureFlagRepository = $featureFlagRepository;
    }

    /**
     * @param string $flag
     * @throws Tx_FeatureFlag_Service_Exception_FeatureNotFound
     * @return boolean
     */
    public function isFeatureEnabled($flag)
    {
        if (false === array_key_exists($flag, $this->cachedFlags)) {
            try {
                $isEnabled = $this->featureFlagRepository->findByFlag($flag)->isEnabled();
                $this->cachedFlags[$flag] = $isEnabled;
            } catch (Exception $e) {
                throw new Tx_FeatureFlag_Service_Exception_FeatureNotFound('Feature Flag not found: "' . $flag . '"', 1383842028);
            }
        }
        return $this->cachedFlags[$flag];
    }
}