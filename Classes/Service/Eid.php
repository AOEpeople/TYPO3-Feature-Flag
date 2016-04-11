<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE media GmbH <dev@aoemedia.de>
 *  All rights reserved
 *
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * eID script
 *
 * @author Roland Beisel <roland.beisel@aoe.com>
 */
class Tx_FeatureFlag_Service_Eid {

    /**
     * @var Tx_FeatureFlag_Service
     */
    protected $featureFlagService;

    /**
     * @var Tx_FeatureFlag_System_Typo3_CacheManager
     */
    protected $cacheManager;

    /**
     * Tx_FeatureFlag_Service_Eid constructor.
     * @param Tx_FeatureFlag_Service $service
     * @param Tx_FeatureFlag_System_Typo3_CacheManager $cacheManager
     */
    public function __construct(Tx_FeatureFlag_Service $service, Tx_FeatureFlag_System_Typo3_CacheManager $cacheManager)
    {
        $this->featureFlagService = $service;
        $this->cacheManager = $cacheManager;

        \TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();
    }

    /**
     * Process request
     * @throws Tx_FeatureFlag_Service_Exception_ActionNotFound
     */
    public function processRequest() {
        $action = GeneralUtility::_GP('action');
        $featureName = GeneralUtility::_GP('feature');

        switch ($action) {
            case 'activate':
                $this->featureFlagService->updateFeatureFlag($featureName, true);
                break;
            case 'deactivate':
                $this->featureFlagService->updateFeatureFlag($featureName, false);
                break;
            default:
                throw new Tx_FeatureFlag_Service_Exception_ActionNotFound('Action not found');
        }

        $this->cacheManager->clearPageCache();
    }
}

if (false === defined('PHPUNIT_ACTIVE')) {
    /** @var Tx_FeatureFlag_Service_Eid $featureFlagServiceEid */
    $featureFlagServiceEid = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Extbase\Object\ObjectManager::class)->get(Tx_FeatureFlag_Service_Eid::class);

    $featureFlagServiceEid->processRequest();
}