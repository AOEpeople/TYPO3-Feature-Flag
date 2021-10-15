<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_FeatureFlag_System_Typo3_Task_FlagEntries'] = [
    'extension' => 'feature_flag',
    'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
        'locallang_db.xml:tx_featureflag_system_typo3_task_flagentries.title',
    'description' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
        'locallang_db.xml:tx_featureflag_system_typo3_task_flagentries.description'
];

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys']['feature_flag'] = [
        'EXT:feature_flag/Classes/System/Typo3/Cli.php',
        '_CLI_feature_flag'
    ];
}

$confArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);
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
