<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

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

t3lib_extMgm::addTCAcolumns('tt_content', array(
    'tx_featureflag_featureflag' => array(
        'exclude' => 1,
        'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tt_content.feature_flag',
        'config' => array(
            'type' => 'select',
            'foreign_table' => 'tx_featureflag_domain_model_featureflag',
            'items' => array(
                array('', '')
            ),
            'size' => 1,
            'minitems' => 0,
            'maxitems' => 1,
        )
    )), 1);
t3lib_extMgm::addToAllTCAtypes('tt_content', '--div--;LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tt_content.feature_flag,tx_featureflag_featureflag');

t3lib_extMgm::addTCAcolumns('pages', array(
    'tx_featureflag_featureflag' => array(
        'exclude' => 1,
        'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:pages.feature_flag',
        'config' => array(
            'type' => 'select',
            'foreign_table' => 'tx_featureflag_domain_model_featureflag',
            'items' => array(
                array('', 0)
            ),
            'size' => 1,
            'minitems' => 0,
            'maxitems' => 1,
        )
    )), 1);
t3lib_extMgm::addToAllTCAtypes('pages', '--div--;LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:pages.feature_flag,tx_featureflag_featureflag');