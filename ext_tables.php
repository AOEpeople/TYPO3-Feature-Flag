<?php
use Aoe\FeatureFlag\System\Typo3\TCA;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die();

$GLOBALS['TCA']['tx_featureflag_domain_model_featureflag']['ctrl']['security']['ignorePageTypeRestriction'] = true;
$GLOBALS['TCA']['tx_featureflag_domain_model_mapping']['ctrl']['security']['ignorePageTypeRestriction'] = true;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = TCA::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = TCA::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][IconFactory::class]['overrideIconOverlay'][] = TCA::class;

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
$iconRegistry->registerIcon(
    'record-has-feature-flag-which-is-visible',
    BitmapIconProvider::class,
    [
        'source' => 'EXT:feature_flag/Resources/Public/Icons/TBE/FeatureFlag.gif'
    ]
);
$iconRegistry->registerIcon(
    'record-has-feature-flag-which-is-hidden',
    BitmapIconProvider::class,
    [
        'source' => 'EXT:feature_flag/Resources/Public/Icons/TBE/FeatureFlagHidden.gif'
    ]
);
