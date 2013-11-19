<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 AOE GmbH <dev@aoemedia.de>
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
 * @author Kevin Schu <kevin.schu@aoemedia.de>
 * @author Matthias Gutjahr <matthias.gutjahr@aoemedia.de>
 */
class Tx_FeatureFlag_Service
{
    /**
     * @var int
     */
    const BEHAVIOR_HIDE = 0;

    /**
     * @var int
     */
    const BEHAVIOR_SHOW = 1;

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