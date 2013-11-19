<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

$TCA['tx_featureflag_domain_model_mapping'] = array(
    'ctrl' => $TCA['tx_featureflag_domain_model_mapping']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'feature_flag,foreign_table_uid,foreign_table_name,foreign_table_column',
    ),
    'types' => array(
        '1' => array('showitem' => 'feature_flag,foreign_table_uid,foreign_table_name,foreign_table_column'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'uid' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.uid',
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        'pid' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.pid',
            'config' => array(
                'type' => 'passthrough'
            )
        ),
        'tstamp' => Array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.tstamp',
            'config' => Array(
                'type' => 'none',
                'format' => 'date',
                'eval' => 'date',
            )
        ),
        'crdate' => Array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.crdate',
            'config' => Array(
                'type' => 'none',
                'format' => 'date',
                'eval' => 'date',
            )
        ),
        'feature_flag' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.feature_flag',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'tx_featureflag_domain_model_featureflag',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
            ),
        ),
        'foreign_table_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.foreign_table_uid',
            'config' => array(
                'type' => 'none',
                'size' => 30,
            ),
        ),
        'foreign_table_name' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.foreign_table_name',
            'config' => array(
                'type' => 'none',
                'size' => 30,
            ),
        ),
        'foreign_table_column' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.foreign_table_column',
            'config' => array(
                'type' => 'none',
                'size' => 30,
            ),
        ),
        'behavior' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.behavior',
            'config' => array(
                'type' => 'none',
                'size' => 5,
            ),
        ),
    ),
);