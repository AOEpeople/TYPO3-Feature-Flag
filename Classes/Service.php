<?php
/**
 * Class Configuration
 * @package FeatureFlag
 */
class Tx_FeatureFlag_Service
{
    /**
     * @var Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    private $featureFlagRepository;

    /**
     * @param Tx_FeatureFlag_Domain_Repository_FeatureFlag $featureFlagRepository
     */
    public function injectFeatureFlagRepository(Tx_FeatureFlag_Domain_Repository_FeatureFlag $featureFlagRepository)
    {
        $this->featureFlagRepository = $featureFlagRepository;
    }

    /**
     * @param $flag
     * @return boolean
     */
    public function isFeatureEnabled($flag)
    {
        return $this->featureFlagRepository->findByFlag($flag)->isEnabled();
    }
}