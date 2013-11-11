<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_FeatureFlag_System_Typo3_Task_FlagEntries'] = array(
    'extension' => $_EXTKEY,
    'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_system_typo3_task_flagentries.title',
    'description' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_system_typo3_task_flagentries.description'
);
