<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_featureflag_domain_model_featureflag');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_featureflag_domain_model_mapping');

$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
    'EXT:feature_flag/Classes/System/Typo3/TCA.php:Tx_FeatureFlag_System_Typo3_TCA';
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] =
    'EXT:feature_flag/Classes/System/Typo3/TCA.php:Tx_FeatureFlag_System_Typo3_TCA';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_iconworks.php']['overrideIconOverlay'][] =
    'EXT:feature_flag/Classes/System/Typo3/TCA.php:Tx_FeatureFlag_System_Typo3_TCA';

$GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayPriorities'] = array_merge(
    array(
        'feature_flag_hidden',
        'feature_flag',
    ),
    $GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayPriorities']
);
$GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayNames']['feature_flag'] =
    'extensions-feature_flag-feature_flag';
$GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayNames']['feature_flag_hidden'] =
    'extensions-feature_flag-feature_flag_hidden';

\TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons(
    array(
        'feature_flag' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('feature_flag') .
            'Resources/Public/Icons/TBE/FeatureFlag.gif',
        'feature_flag_hidden' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('feature_flag') .
            'Resources/Public/Icons/TBE/FeatureFlagHidden.gif'
    ),
    'feature_flag'
);
