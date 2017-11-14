<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_featureflag_domain_model_featureflag');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_featureflag_domain_model_mapping');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = Tx_FeatureFlag_System_Typo3_TCA::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = Tx_FeatureFlag_System_Typo3_TCA::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Imaging\IconFactory::class]['overrideIconOverlay'][] = Tx_FeatureFlag_System_Typo3_TCA::class;

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'record-has-feature-flag-which-is-visible',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    [
        'source' => 'EXT:feature_flag/Resources/Public/Icons/TBE/FeatureFlag.gif'
    ]
);
$iconRegistry->registerIcon(
    'record-has-feature-flag-which-is-hidden',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    [
        'source' => 'EXT:feature_flag/Resources/Public/Icons/TBE/FeatureFlagHidden.gif'
    ]
);
