<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:tx_featureflag_domain_model_featureflag',
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
    'columns' => [
        'description' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:tx_featureflag_domain_model_featureflag.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'flag' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:tx_featureflag_domain_model_featureflag.flag',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'alphanum_x,trim,unique',
                'required' => true,
            ],
        ],
        'enabled' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:tx_featureflag_domain_model_featureflag.enabled',
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
