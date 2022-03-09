<?php
use Aoe\FeatureFlag\Form\Element\FeatureFlagBehaviourFormSelectElement;
use Aoe\FeatureFlag\Form\Element\FeatureFlagFormSelectElement;
use Aoe\FeatureFlag\Form\Element\InfoTextElement;
use Aoe\FeatureFlag\System\Typo3\Task\FlagEntriesTask;

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][FlagEntriesTask::class] = [
    'extension' => 'feature_flag',
    'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
        'locallang_db.xml:tx_featureflag_system_typo3_task_flagentries.title',
    'description' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
        'locallang_db.xml:tx_featureflag_system_typo3_task_flagentries.description'
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys']['feature_flag'] = [
    'EXT:feature_flag/Classes/System/Typo3/Cli.php',
    '_CLI_feature_flag'
];

$confArray = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    "TYPO3\\CMS\\Core\\Configuration\\ExtensionConfiguration"
)->get('feature_flag');

if ($confArray['enableEidMode'] === true) {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['featureflag'] = 'EXT:feature_flag/Classes/Service/Eid.php';
}

/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
$signalSlotDispatcher->connect(
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::class,
    'tcaIsBeingBuilt',
    \Aoe\FeatureFlag\System\Typo3\TcaPostProcessor::class,
    'postProcessTca'
);

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
