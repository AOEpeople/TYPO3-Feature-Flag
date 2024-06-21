<?php

namespace Aoe\FeatureFlag\Tests\Unit;

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

use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class BaseTestCase extends UnitTestCase
{
    use ProphecyTrait;

    /**
     * Whether global variables should be backed up
     *
     * Don't backup globals, otherwise other unittests will fail
     *
     * Why can other unittests fail, if we backup global variables?
     * If variable $GLOBALS['TYPO3_DB'] will be backup-ed (object will be serialized and unserialized), than
     * the object loose it's properties (e.g. the variable \TYPO3\CMS\Core\Database\DatabaseConnection->link)
     *
     * @var boolean
     */
    protected $backupGlobals = false;
}
