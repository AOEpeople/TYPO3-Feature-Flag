<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE media GmbH <dev@aoemedia.de>
 *  All rights reserved
 *
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * @package FeatureFlag
 * @subpackage System_Typo3
 * @author Roland Beisel <roland.beisel@aoe.com>
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
     * clears cache for all pages
     */
    public function clearPageCache()
    {
        /** @var TYPO3\CMS\Core\DataHandling\DataHandler $tce */
        $tce = $this->objectManager->get(TYPO3\CMS\Core\DataHandling\DataHandler::class);
        $tce->admin = 1;
        $tce->start(array(), array());
        $tce->clear_cacheCmd('all');
    }
}