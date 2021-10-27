<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

return [
    'ctrl' => [
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping',
        'label' => 'uid',
        'label_alt' => 'foreign_table_uid,foreign_table_name,foreign_table_column',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [],
        'iconfile' => 'EXT:feature_flag/Resources/Public/Icons/TCA/Mapping.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'uid,pid,crdate,tstamp,feature_flag,foreign_table_uid,foreign_table_name,behavior',
    ],
    'columns' => [
        'uid' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.uid',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'pid' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xml:LGL.pid',
            'config' => [
                'type' => 'passthrough',
            ]
        ],
        'tstamp' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.tstamp',
            'config' => [
                'type' => 'text',
                'size' => 10,
                'format' => 'date',
                'eval' => 'date',
                'readOnly' => 1,
            ]
        ],
        'crdate' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.crdate',
            'config' => [
                'type' => 'text',
                'size' => 10,
                'format' => 'date',
                'eval' => 'date',
                'readOnly' => 1,
            ]
        ],
        'feature_flag' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.feature_flag',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_featureflag_domain_model_featureflag',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'readOnly' => 1,
            ],
        ],
        'foreign_table_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
                'locallang_db.xml:tx_featureflag_domain_model_mapping.foreign_table_uid',
            'config' => [
                'type' => 'text',
                'size' => 10,
                'readOnly' => 1,
            ],
        ],
        'foreign_table_name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
                'locallang_db.xml:tx_featureflag_domain_model_mapping.foreign_table_name',
            'config' => [
                'type' => 'text',
                'size' => 10,
                'readOnly' => 1,
            ],
        ],
        'behavior' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.behavior',
            'config' => [
                'type' => 'text',
                'size' => 10,
                'readOnly' => 1,
            ],
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'uid,pid,crdate,tstamp,feature_flag,foreign_table_uid,foreign_table_name,behavior'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ]
];
