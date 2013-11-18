<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);
if (isset ($config['tables'])) {
    $tables = explode(',', $config ['tables']);
    foreach ($tables as $table) {
        t3lib_extMgm::addTCAcolumns($table, array(
                'tx_featureflag_hide' => array(
                    'exclude' => 1,
                    'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:feature_flag_hide',
                    'config' => array(
                        'type' => 'user',
                        'userFunc' => 'Tx_FeatureFlag_System_Typo3_TCA->renderSelect',
                    )
                ),
                'tx_featureflag_show' => array(
                    'exclude' => 1,
                    'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:feature_flag_show',
                    'config' => array(
                        'type' => 'user',
                        'userFunc' => 'Tx_FeatureFlag_System_Typo3_TCA->renderSelect',
                    )
                )
            ),
            1);
        t3lib_extMgm::addToAllTCAtypes($table, '--div--;LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:feature_flag,tx_featureflag_hide,tx_featureflag_show');
    }
}

t3lib_extMgm::allowTableOnStandardPages('tx_featureflag_domain_model_featureflag');
$TCA['tx_featureflag_domain_model_featureflag'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_featureflag',
        'label' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => TRUE,
        'searchFields' => 'description,flag,',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'rootLevel' => 1,
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/FeatureFlag.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/TCA/FeatureFlag.gif'
    ),
);

t3lib_extMgm::allowTableOnStandardPages('tx_featureflag_domain_model_mapping');
$TCA['tx_featureflag_domain_model_mapping'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping',
        'label' => 'uid',
        'label_alt' => 'foreign_table_uid,foreign_table_name,foreign_table_column',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => TRUE,
        'delete' => 'deleted',
        'enablecolumns' => array(),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Mapping.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/TCA/Mapping.gif'
    ),
);

$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:feature_flag/Classes/System/Typo3/TCA.php:Tx_FeatureFlag_System_Typo3_TCA';
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:feature_flag/Classes/System/Typo3/TCA.php:Tx_FeatureFlag_System_Typo3_TCA';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_iconworks.php']['overrideIconOverlay'][] = 'EXT:feature_flag/Classes/System/Typo3/TCA.php:Tx_FeatureFlag_System_Typo3_TCA';

$GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayPriorities'] = array_merge(
    array(
        'feature_flag_hidden',
        'feature_flag',
    ),
    $GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayPriorities']
);
$GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayNames']['feature_flag'] = 'extensions-feature_flag-feature_flag';
$GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayNames']['feature_flag_hidden'] = 'extensions-feature_flag-feature_flag_hidden';

t3lib_SpriteManager::addSingleIcons(array(
    'feature_flag' => t3lib_extMgm::extRelPath($_EXTKEY).'Resources/Public/Icons/TBE/FeatureFlag.gif',
    'feature_flag_hidden' => t3lib_extMgm::extRelPath($_EXTKEY).'Resources/Public/Icons/TBE/FeatureFlagHidden.gif'
), $_EXTKEY);
