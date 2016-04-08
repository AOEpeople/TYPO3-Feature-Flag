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
 * @author Kevin Schu <kevin.schu@aoe.com>
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
     * @var Tx_FeatureFlag_System_Typo3_CacheManager
     */
    private $cacheManager;

    /**
     * @var Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    private $featureFlagRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     */
    private $persistenceManager;

    /**
     * @var array
     */
    private $cachedFlags = array();

    /**
     * Tx_FeatureFlag_Service constructor.
     * @param Tx_FeatureFlag_Domain_Repository_FeatureFlag $featureFlagRepository
     * @param Tx_FeatureFlag_Domain_Repository_Mapping $mappingRepository
     * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
     */
    public function __construct(
        Tx_FeatureFlag_System_Typo3_CacheManager $cacheManager,
        Tx_FeatureFlag_Domain_Repository_FeatureFlag $featureFlagRepository,
        \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
    )
    {
        $this->cacheManager = $cacheManager;
        $this->featureFlagRepository = $featureFlagRepository;
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param $flag
     * @return Tx_FeatureFlag_Domain_Model_FeatureFlag
     * @throws Tx_FeatureFlag_Service_Exception_FeatureNotFound
     * @return boolean
     */
    protected function getFeatureFlag($flag)
    {
        if (false === array_key_exists($flag, $this->cachedFlags)) {
            $flagModel = $this->featureFlagRepository->findByFlag($flag);
            if (false === $flagModel instanceof Tx_FeatureFlag_Domain_Model_FeatureFlag) {
                throw new Tx_FeatureFlag_Service_Exception_FeatureNotFound(
                    'Feature Flag not found: "' . $flag . '"',
                    1383842028
                );
            }
            $this->cachedFlags[$flag] = $flagModel;
        }
        return $this->cachedFlags[$flag];
    }

    /**
     * @param $flag
     * @return bool
     * @throws Tx_FeatureFlag_Service_Exception_FeatureNotFound
     */
    public function isFeatureEnabled($flag)
    {
        return $this->getFeatureFlag($flag)->isEnabled();
    }

    /**
     * Updates a featureFlag and sets its enabled property
     *
     * @param $flag
     * @param $enabled
     * @throws Tx_FeatureFlag_Service_Exception_FeatureNotFound
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function updateFeatureFlag($flag, $enabled)
    {
        $flagModel = $this->getFeatureFlag($flag);
        $flagModel->setEnabled($enabled);

        // update entry in db
        $this->featureFlagRepository->update($flagModel);
        $this->persistenceManager->persistAll();
    }
}
