<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package FeatureFlag
 * @author Roland Beisel <roland.beisel@aoe.com>
 */
class Tx_FeatureFlag_Service_Eid
{
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
     */
    public function __construct(Tx_FeatureFlag_Service $service, Tx_FeatureFlag_System_Typo3_CacheManager $cacheManager)
    {
        $confArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);

        #var_dump($confArray);
        #die;
        $this->featureFlagService = $service;
        $this->cacheManager = $cacheManager;

        \TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();
    }

    /**
     * Process request
     * @throws Tx_FeatureFlag_Service_Exception_ActionNotFound
     */
    public function processRequest()
    {
        $action = GeneralUtility::_GP('action');
        $featureName = GeneralUtility::_GP('feature');

        $response = null;
        switch ($action) {
            case 'activate':
                $this->featureFlagService->updateFeatureFlag($featureName, true);
                break;
            case 'deactivate':
                $this->featureFlagService->updateFeatureFlag($featureName, false);
                break;
            case 'flushcaches':
                $this->cacheManager->clearAllCaches();
                break;
            case 'flagentries':
                $this->featureFlagService->flagEntries();
                break;
            case 'status':
                $response = $this->featureFlagService->isFeatureEnabled($featureName);
                break;
            default:
                throw new Tx_FeatureFlag_Service_Exception_ActionNotFound('Action not found');
                break;
        }

        echo json_encode(array('status' => 200, 'response' => $response));
    }
}

if (false === defined('PHPUNIT_ACTIVE')) {
    /** @var Tx_FeatureFlag_Service_Eid $featureFlagServiceEid */
    $featureFlagServiceEid = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class)
        ->get(Tx_FeatureFlag_Service_Eid::class);

    $featureFlagServiceEid->processRequest();
}