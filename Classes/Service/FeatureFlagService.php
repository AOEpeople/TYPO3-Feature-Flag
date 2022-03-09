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

use Aoe\FeatureFlag\Domain\Model\FeatureFlag;
use Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository;
use Aoe\FeatureFlag\Service\Exception\FeatureNotFoundException;
use Aoe\FeatureFlag\System\Typo3\Configuration;
use RuntimeException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

class FeatureFlagService
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
     * @var FeatureFlagRepository
     */
    private $featureFlagRepository;

    /**
     * @var PersistenceManagerInterface
     */
    private $persistenceManager;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var array
     */
    private $cachedFlags = [];

    /**
     * @param FeatureFlagRepository $featureFlagRepository
     * @param PersistenceManagerInterface $persistenceManager
     * @param Configuration $configuration
     */
    public function __construct(
        FeatureFlagRepository $featureFlagRepository,
        PersistenceManagerInterface $persistenceManager,
        Configuration $configuration
    ) {
        $this->featureFlagRepository = $featureFlagRepository;
        $this->persistenceManager = $persistenceManager;
        $this->configuration = $configuration;
    }

    /**
     * @param string $flag
     * @return FeatureFlag
     * @throws FeatureNotFoundException
     * @throws RuntimeException
     * @return boolean
     */
    protected function getFeatureFlag($flag)
    {
        if (false === array_key_exists($flag, $this->cachedFlags)) {
            $flagModel = $this->featureFlagRepository->findByFlag($flag);
            if (false === $flagModel instanceof FeatureFlag) {
                throw new FeatureNotFoundException('Feature Flag not found: "' . $flag . '"', 1383842028);
            }
            $this->cachedFlags[$flag] = $flagModel;
        }
        return $this->cachedFlags[$flag];
    }

    /**
     * @param $flag
     * @return bool
     * @throws FeatureNotFoundException
     */
    public function isFeatureEnabled($flag)
    {
        return $this->getFeatureFlag($flag)->isEnabled();
    }

    /**
     * @param $flag
     * @param $enabled
     * @throws FeatureNotFoundException
     * @throws IllegalObjectTypeException
     */
    public function updateFeatureFlag($flag, $enabled)
    {
        $flagModel = $this->getFeatureFlag($flag);
        $flagModel->setEnabled($enabled);

        $this->featureFlagRepository->update($flagModel);
        $this->persistenceManager->persistAll();

        $this->cachedFlags[$flag] = $flagModel;
    }

    /**
     * Flags entries in database
     */
    public function flagEntries()
    {
        foreach ($this->configuration->getTables() as $table) {
            $this->featureFlagRepository->updateFeatureFlagStatusForTable($table);
        }
    }
}