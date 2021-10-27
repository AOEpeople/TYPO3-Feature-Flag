<?php
namespace Aoe\FeatureFlag\Command;

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

use Aoe\FeatureFlag\Service\Exception\FeatureNotFoundException;
use Aoe\FeatureFlag\Service\FeatureFlagService;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Container\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Scheduler\Scheduler;

abstract class AbstractCommand extends Command
{
    /**
     * @var FeatureFlagService
     */
    protected $featureFlagService;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Scheduler
     */
    protected $scheduler;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->featureFlagService = $this->objectManager->get(FeatureFlagService::class);
        $this->scheduler = $this->objectManager->get(Scheduler::class);
    }

    /**
     * Enable or disable features. $features can be a comma-separated list of feature names
     * @param String $features
     * @param $enabled
     * @throws FeatureNotFoundException
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    protected function setFeatureStatus($features, $enabled)
    {
        $features = array_map('trim', explode(',', $features));
        foreach ($features as $feature) {
            echo $feature;
            $this->featureFlagService->updateFeatureFlag($feature, $enabled);
        }
        $this->featureFlagService->flagEntries();
    }
}
