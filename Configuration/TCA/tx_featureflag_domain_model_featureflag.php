<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

return [
    'ctrl' => [
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/' . 'locallang_db.xml:tx_featureflag_domain_model_featureflag',
        'label' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'searchFields' => 'description,flag,',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'rootLevel' => 1,
        'iconfile' => 'EXT:feature_flag/Resources/Public/Icons/TCA/FeatureFlag.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'description,flag,enabled',
    ],
    'columns' => [
        'description' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/'
                . 'locallang_db.xml:tx_featureflag_domain_model_featureflag.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'flag' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_featureflag.flag',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'alphanum_x,trim,required,unique'
            ],
        ],
        'enabled' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_featureflag.enabled',
            'config' => [
                'type' => 'check',
            ],
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'description,flag,enabled'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ]
];
