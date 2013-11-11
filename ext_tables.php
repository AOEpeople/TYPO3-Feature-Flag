<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);
if (isset ($config['tables'])) {
    $tables = explode(',', $config ['tables']);
    foreach ($tables as $table) {
        t3lib_extMgm::addTCAcolumns($table, array(
                'tx_featureflag_hide' => array(
                    'exclude' => 1,
                    'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:feature_flag_hide',
                    'config' => array(
                        'type' => 'select',
                        'foreign_table' => 'tx_featureflag_domain_model_featureflag',
                        'items' => array(
                            array('', 0)
                        ),
                        'size' => 1,
                        'minitems' => 0,
                        'maxitems' => 1,
                        'MM' => 'tx_featureflag_mapping',
                        'MM_match_fields' => array(
                            'local_table' => $table,
                            'local_column' => 'tx_featureflag_hide',
                        ),
                    )
                ),
                'tx_featureflag_show' => array(
                    'exclude' => 1,
                    'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:feature_flag_show',
                    'config' => array(
                        'type' => 'select',
                        'foreign_table' => 'tx_featureflag_domain_model_featureflag',
                        'items' => array(
                            array('', 0)
                        ),
                        'size' => 1,
                        'minitems' => 0,
                        'maxitems' => 1,
                        'MM' => 'tx_featureflag_mapping',
                        'MM_match_fields' => array(
                            'local_table' => $table,
                            'local_column' => 'tx_featureflag_show',
                        ),
                    )
                )
            ),
            1);
        t3lib_extMgm::addToAllTCAtypes($table, '--div--;LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:feature_flag,tx_featureflag_hide,tx_featureflag_show');
    }
}

t3lib_extMgm::allowTableOnStandardPages('tx_featureflag_domain_model_featureflag');
$TCA['tx_featureflag_domain_model_featureflag'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_featureflag',
        'label' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => TRUE,
        'searchFields' => 'description,flag,',
        'rootLevel' => 1,
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/FeatureFlag.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/TCA/FeatureFlag.gif'
    ),
);