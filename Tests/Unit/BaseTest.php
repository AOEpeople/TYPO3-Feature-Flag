<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 AOE GmbH <dev@aoe.com>
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
 * @subpackage Tests
 */
abstract class Tx_FeatureFlag_Tests_Unit_BaseTest extends \Nimut\TestingFramework\TestCase\UnitTestCase
{
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

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        /**
         * Don't call parent method, otherwise other unittests will fail
         *
         * Why can other unittests fail, if we call parent method?
         * If we call parent method, than a strange PHP-fatal-error in Test-Class Tx_FeatureFlag_Tests_Unit_System_Db_SqlFactoryTest occurs!
         * The PHP-fatal-error occurs, because than $GLOBALS['TYPO3_DB'] is NULL!
         */
    }
}
