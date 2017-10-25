<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping',
        'label' => 'uid',
        'label_alt' => 'foreign_table_uid,foreign_table_name,foreign_table_column',
        'label_alt_force' => 1,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => array(),
        'iconfile' => 'EXT:feature_flag/Resources/Public/Icons/TCA/Mapping.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'uid,pid,crdate,tstamp,feature_flag,foreign_table_uid,foreign_table_name,behavior',
    ),
    'columns' => array(
        'uid' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.uid',
            'config' => array(
                'type' => 'passthrough',
            )
        ),
        'pid' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.pid',
            'config' => array(
                'type' => 'passthrough',
            )
        ),
        'tstamp' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.tstamp',
            'config' => array(
                'type' => 'text',
                'size' => 10,
                'format' => 'date',
                'eval' => 'date',
                'readOnly' => 1,
            )
        ),
        'crdate' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.crdate',
            'config' => array(
                'type' => 'text',
                'size' => 10,
                'format' => 'date',
                'eval' => 'date',
                'readOnly' => 1,
            )
        ),
        'feature_flag' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.feature_flag',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_featureflag_domain_model_featureflag',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'readOnly' => 1,
            ),
        ),
        'foreign_table_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
                'locallang_db.xml:tx_featureflag_domain_model_mapping.foreign_table_uid',
            'config' => array(
                'type' => 'text',
                'size' => 10,
                'readOnly' => 1,
            ),
        ),
        'foreign_table_name' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/' .
                'locallang_db.xml:tx_featureflag_domain_model_mapping.foreign_table_name',
            'config' => array(
                'type' => 'text',
                'size' => 10,
                'readOnly' => 1,
            ),
        ),
        'behavior' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_mapping.behavior',
            'config' => array(
                'type' => 'text',
                'size' => 10,
                'readOnly' => 1,
            ),
        ),
    ),
    'types' => array(
        '1' => array('showitem' => 'uid,pid,crdate,tstamp,feature_flag,foreign_table_uid,foreign_table_name,behavior'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    )
);
