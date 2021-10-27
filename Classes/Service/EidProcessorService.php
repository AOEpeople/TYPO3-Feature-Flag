<?php
namespace Aoe\FeatureFlag\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 AOE GmbH <dev@aoe.com>
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

use Aoe\FeatureFlag\Service\Exception\ActionNotFoundException;
use Aoe\FeatureFlag\Service\Exception\FeatureNotFoundException;
use Aoe\FeatureFlag\System\Typo3\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;

class EidProcessorService
{
    /**
     * @var FeatureFlagService
     */
    protected $featureFlagService;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @param FeatureFlagService $service
     * @param CacheManager $cacheManager
     */
    public function __construct(
        FeatureFlagService $service,
        CacheManager $cacheManager
    ) {
        $this->featureFlagService = $service;
        $this->cacheManager = $cacheManager;
    }

    /**
     * Process request
     * @throws ActionNotFoundException
     * @throws FeatureNotFoundException
     * @throws IllegalObjectTypeException
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
            case 'flagentries':
                $this->featureFlagService->flagEntries();
                break;
            case 'status':
                $response = $this->featureFlagService->isFeatureEnabled($featureName);
                break;
            default:
                throw new ActionNotFoundException('Action not found', 1515750886);
        }

        echo json_encode(['status' => 200, 'response' => $response]);
    }
}