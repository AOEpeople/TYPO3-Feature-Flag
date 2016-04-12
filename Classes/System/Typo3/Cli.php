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
 * @author Kevin Schu <kevin.schu@aoe.com>
 * @author Roland Beisel <roland.beisel@aoe.com>
 */
class Tx_FeatureFlag_System_Typo3_Cli extends \TYPO3\CMS\Core\Controller\CommandLineController
{
    /**
     * @var string
     */
    protected $extKey = 'feature_flag';
    /**
     * @var string
     */
    protected $prefixId = 'tx_featureflag_system_typo3_cli';
    /**
     * @var string
     */
    protected $scriptRelPath = 'Classes/System/Typo3/Cli.php';

    /**
     * @var TYPO3\CMS\Scheduler\Scheduler
     */
    protected $scheduler;

    /**
     * @var Tx_FeatureFlag_Service
     */
    protected $service;

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        if (!defined('TYPO3_cliMode')) {
            die('Access denied: CLI only.');
        }
        $this->cli_options = array_merge($this->cli_options, array());
        $this->cli_help = array_merge($this->cli_help, array(
            'name' => $this->prefixId,
            'synopsis' => $this->extKey . ' command',
            'description' => 'This script can flag all configured tables by feature flags.',
            'examples' => 'typo3/cli_dispatch.phpsh ' . $this->extKey . ' [flagEntries]',
            'author' => '(c) 2013 AOE GmbH <dev@aoe.com>',
        ));
        $this->conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);

        $this->scheduler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Scheduler\\Scheduler');

        $this->service = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class)
            ->get(Tx_FeatureFlag_Service::class);
    }

    /**
     * @param $argv
     * @return int
     */
    public function main($argv)
    {
        $this->init();
        try {
            switch ($this->getAction()) {
                case 'activate':
                    $this->setFeatureStatus($this->getArgument(), true);
                    break;
                case 'deactivate':
                    $this->setFeatureStatus($this->getArgument(), false);
                    break;
                case 'flagEntries':
                    $this->flagEntries();
                    break;
                case 'flushCaches':
                    $this->flushCaches();
                    break;
                default:
                    $this->cli_help();
                    break;
            }
        } catch (Exception $e) {
            return 1;
        }

        return 0;
    }

    /**
     * @return string
     */
    private function getAction()
    {
        return (string)$this->cli_args['_DEFAULT'][1];
    }

    /**
     * @return string
     */
    private function getArgument()
    {
        return (string)$this->cli_args['_DEFAULT'][2];
    }

    /**
     * @return void
     */
    private function init()
    {
        $this->cli_validateArgs();
    }

    /**
     * check if scheduler is installed
     *
     * @return boolean
     */
    private function isSchedulerInstalled()
    {
        return is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys']['scheduler']);
    }

    /**
     * @return int
     */
    private function getSchedulerTaskUid()
    {
        foreach ($this->scheduler->fetchTasksWithCondition() as $task) {
            if ($task instanceof Tx_FeatureFlag_System_Typo3_Task_FlagEntries) {
                $taskUid = $task->getTaskUid();
            }
        }

        if (null === $taskUid) {
            throw new RuntimeException('scheduler task for feature_flag was not found');
        }
        return $taskUid;
    }

    /**
     * @throws RuntimeException
     */
    private function flagEntries()
    {
        if ($this->isSchedulerInstalled()) {
            $taskUid = $this->getSchedulerTaskUid();
            $task = $this->scheduler->fetchTask($taskUid);

            if ($this->scheduler->isValidTaskObject($task)) {
                $this->scheduler->executeTask($task);
            } else {
                throw new RuntimeException('task-object of feature-flag-task is not valid!');
            }
        }
    }

    /**
     * Enable or disable features. $features can be a comma-separated list of feature names
     * @param String $features
     */
    private function setFeatureStatus($features, $enabled)
    {
        $features = array_map('trim', explode(',', $features));
        foreach ($features as $feature) {
            echo $feature;
            $this->service->updateFeatureFlag($feature, $enabled);
        }
        $this->service->flagEntries();
    }

    /**
     * Clear all page caches
     */
    private function flushCaches()
    {
        /** @var Tx_FeatureFlag_System_Typo3_CacheManager $cacheManager */
        $cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class)
            ->get(Tx_FeatureFlag_System_Typo3_CacheManager::class);
        $cacheManager->clearAllCaches();
    }
}

/** @var Tx_FeatureFlag_System_Typo3_Cli $cli */
$cli = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_FeatureFlag_System_Typo3_Cli');
exit($cli->main($_SERVER['argv']));
