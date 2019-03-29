<?php

namespace Aoe\FeatureFlag\Command;
use Aoe\FeatureFlag\Service;
use Aoe\FeatureFlag\System\Typo3\Task\FlagEntries;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Scheduler\Scheduler;

/**
* @package FeatureFlag
*/
abstract class AbstractCommand extends Command {

    /**
     * @var Service
     */
    protected $service;
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
        $this->service = $this->objectManager->get(Service::class);
        $this->scheduler = $this->objectManager->get(Scheduler::class);
    }

    /**
     * Enable or disable features. $features can be a comma-separated list of feature names
     * @param String $features
     * @param $enabled
     * @throws Service\Exception\FeatureNotFound
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    protected function setFeatureStatus($features, $enabled)
    {
        $features = array_map('trim', explode(',', $features));
        foreach ($features as $feature) {
            echo $feature;
            $this->service->updateFeatureFlag($feature, $enabled);
        }
        $this->service->flagEntries();
    }

    /**
     * @throws RuntimeException
     * @throws \Throwable
     */
    protected function flagEntries()
    {
        $this->service->flagEntries();
    }
}