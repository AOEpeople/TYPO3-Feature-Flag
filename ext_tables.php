<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);
if (isset ($config['tables'])) {
    $tables = explode(',', $config ['tables']);
    foreach ($tables as $table) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            $table,
            array(
                'tx_featureflag_info' => array(
                    'exclude' => 1,
                    'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_info.label',
                    'config' => array(
                        'type' => 'user',
                        'userFunc' => 'Tx_FeatureFlag_System_Typo3_TCA->renderInfo',
                    )
                ),
                'tx_featureflag_flag' => array(
                    'exclude' => 1,
                    'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_flag',
                    'config' => array(
                        'type' => 'user',
                        'userFunc' => 'Tx_FeatureFlag_System_Typo3_TCA->renderSelectForFlag',
                    )
                ),
                'tx_featureflag_behavior' => array(
                    'exclude' => 1,
                    'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_behavior',
                    'config' => array(
                        'type' => 'user',
                        'userFunc' => 'Tx_FeatureFlag_System_Typo3_TCA->renderSelectForBehavior',
                    )
                )
            )
        );
        $TCA[$table]['palettes']['tx_featureflag'] = array('showitem' => 'tx_featureflag_flag,tx_featureflag_behavior');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            $table,
            '--div--;LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:feature_flag,tx_featureflag_info,--palette--;;tx_featureflag'
        );
    }
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_featureflag_domain_model_featureflag');
$TCA['tx_featureflag_domain_model_featureflag'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_featureflag',
        'label' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => true,
        'searchFields' => 'description,flag,',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'rootLevel' => 1,
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/FeatureFlag.php',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/TCA/FeatureFlag.gif'
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_featureflag_domain_model_mapping');
$TCA['tx_featureflag_domain_model_mapping'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping',
        'label' => 'uid',
        'label_alt' => 'foreign_table_uid,foreign_table_name,foreign_table_column',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => array(),
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Mapping.php',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/TCA/Mapping.gif'
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

\TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons(
    array(
        'feature_flag' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/TBE/FeatureFlag.gif',
        'feature_flag_hidden' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/TBE/FeatureFlagHidden.gif'
    ),
    $_EXTKEY
);
