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

/**
 * @package FeatureFlag
 * @subpackage System_Typo3
 */
class Tx_FeatureFlag_System_Typo3_CacheManager
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    private $objectManager;

    /**
     * Tx_FeatureFlag_System_Typo3_Cache constructor.
     * @param \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
     */
    public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Clear all caches. Therefor it is necessary to login a BE_USER. You have to prevent
     * this function to run on live systems!!!
     */
    public function clearAllCaches()
    {
        /** @var TYPO3\CMS\Core\DataHandling\DataHandler $tce */
        $tce = $this->objectManager->get(TYPO3\CMS\Core\DataHandling\DataHandler::class);
        $tce->start(array(), array());
        $tce->admin = 1;
        $tce->clear_cacheCmd('all');
    }
}