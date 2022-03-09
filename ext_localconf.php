<?php
use Aoe\FeatureFlag\Form\Element\FeatureFlagBehaviourFormSelectElement;
use Aoe\FeatureFlag\Form\Element\FeatureFlagFormSelectElement;
use Aoe\FeatureFlag\Form\Element\InfoTextElement;
use Aoe\FeatureFlag\System\Typo3\Task\FlagEntriesTask;

defined('TYPO3') or die();

/**
 * register scheduler-task in TYPO3
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][FlagEntriesTask::class] = [
    'extension' => 'feature_flag',
    'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
        'locallang_db.xml:tx_featureflag_system_typo3_task_flagentries.title',
    'description' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
        'locallang_db.xml:tx_featureflag_system_typo3_task_flagentries.description'
];

/**
 * register formEngine-nodes in TYPO3
 */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1634655513] = [
    'nodeName' => 'selectFeatureFlag',
    'priority' => '70',
    'class' => FeatureFlagFormSelectElement::class,
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1634655539] = [
    'nodeName' => 'selectFeatureFlagBehaviour',
    'priority' => '70',
    'class' => FeatureFlagBehaviourFormSelectElement::class,
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1634736513] = [
    'nodeName' => 'infoText',
    'priority' => '70',
    'class' => InfoTextElement::class,
];
