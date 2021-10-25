<?php
namespace Aoe\FeatureFlag\Tests\Unit\Domain\Model;

use Aoe\FeatureFlag\Domain\Model\FeatureFlag;
use Aoe\FeatureFlag\Tests\Unit\BaseTest;

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

class FeatureFlagTest extends BaseTest
{
    /**
     * @var FeatureFlag
     */
    private $featureFlag;

    /**
     * (non-PHPdoc)
     * @see TestCase::setUp()
     */
    protected function setUp()
    {
        $this->featureFlag = new FeatureFlag();
    }

    /**
     * (non-PHPdoc)
     * @see TestCase::tearDown()
     */
    protected function tearDown(): void
    {
        $this->featureFlag = null;
        parent::tearDown();
    }

    /**
     * @test
     */
    public function checkProperties()
    {
        $this->featureFlag->setDescription('This is a test description');
        $this->featureFlag->setEnabled(true);
        $this->featureFlag->setFlag('my_new_feature_flag');
        $this->assertTrue($this->featureFlag->isEnabled());
        $this->assertEquals($this->featureFlag->getDescription(), 'This is a test description');
        $this->assertEquals($this->featureFlag->getFlag(), 'my_new_feature_flag');
    }
}
