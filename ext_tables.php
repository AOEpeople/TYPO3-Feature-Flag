<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_featureflag_domain_model_featureflag');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_featureflag_domain_model_mapping');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
    \Aoe\FeatureFlag\System\Typo3\TCA::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] =
    \Aoe\FeatureFlag\System\Typo3\TCA::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Imaging\IconFactory::class]['overrideIconOverlay'][] =
    \Aoe\FeatureFlag\System\Typo3\TCA::class;

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
