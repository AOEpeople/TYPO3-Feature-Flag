<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_featureflag_domain_model_featureflag'] = array(
	'ctrl' => $TCA['tx_featureflag_domain_model_featureflag']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'description,flag,enabled',
	),
	'types' => array(
		'1' => array('showitem' => 'description,flag,enabled'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_domain_model_featureflag.description',
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
                'eval' => 'trim,required'
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
);