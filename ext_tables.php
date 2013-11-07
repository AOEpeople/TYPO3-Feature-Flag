<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Google Tag Manager');
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'DataLayer',
	'Google Tag Manager DataLayer'
);

$TCA['tt_content']['types']['list']['subtypes_excludelist']['googletagmanager_datalayer'] = 'layout,recursive,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist']['googletagmanager_datalayer'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( 'googletagmanager_datalayer', 'FILE:EXT:google_tag_manager/Configuration/FlexForms/datalayer.xml');
?>