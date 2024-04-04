<?php

namespace Aoe\FeatureFlag\Tests\Unit\Command;

use Aoe\FeatureFlag\Command\ActivateFeatureFlagCommand;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2022 AOE GmbH <dev@aoe.com>
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

class ActivateFeatureFlagCommandTest extends AbstractCommandTestCase
{
    public function testShouldRunCommand(): void
    {
        $this->runCommand(ActivateFeatureFlagCommand::class, 'feature1,feature2,feature3');
        $this->assertThatFeaturesAreActivated(['feature1', 'feature2', 'feature3']);
        $this->assertThatFeaturesAreNotDeactivated();
        $this->assertThatInfosAreShown([
            'Activate feature: feature1',
            'Activate feature: feature2',
            'Activate feature: feature3',
            'Update visibility of records (e.g. content elements), which are connected with features',
        ]);
        $this->assertThatEntriesAreFlagged();
    }
}
