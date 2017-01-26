<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/' . 'locallang_db.xml:tx_featureflag_domain_model_featureflag',
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
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('feature_flag') .
            'Resources/Public/Icons/TCA/FeatureFlag.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'description,flag,enabled',
    ),
    'columns' => array(
        'description' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/'
                . 'locallang_db.xml:tx_featureflag_domain_model_featureflag.description',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ),
        ),
        'flag' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_featureflag.flag',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'alphanum_x,trim,required,unique'
            ),
        ),
        'enabled' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_featureflag.enabled',
            'config' => array(
                'type' => 'check',
            ),
        ),
    ),
    'types' => array(
        '1' => array('showitem' => 'description,flag,enabled'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    )
);
