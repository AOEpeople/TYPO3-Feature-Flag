<?php

namespace Aoe\FeatureFlag\Command;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2024 AOE GmbH <dev@aoe.com>
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

use Aoe\FeatureFlag\Service\FeatureFlagService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractCommand extends Command
{
    protected FeatureFlagService $featureFlagService;

    protected SymfonyStyle $inputOutput;

    public function __construct(FeatureFlagService $featureFlagService)
    {
        parent::__construct();
        $this->featureFlagService = $featureFlagService;
    }

    /**
     * Enable or disable features. $features can be a comma-separated list of feature names
     */
    protected function setFeatureStatus(string $features, bool $enabled): void
    {
        $features = array_map('trim', explode(',', $features));
        foreach ($features as $feature) {
            $info = ($enabled) ? 'Activate' : 'Deactivate';
            $this->showInfo($info . ' feature: ' . $feature);
            $this->featureFlagService->updateFeatureFlag($feature, $enabled);
        }

        $this->showInfo('Update visibility of records (e.g. content elements), which are connected with features');
        $this->featureFlagService->flagEntries();
    }

    protected function showInfo(string $info): void
    {
        $this->inputOutput->block($info, 'INFO', 'fg=green', '', false);
    }
}
