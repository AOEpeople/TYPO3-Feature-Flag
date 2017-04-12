<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Feature Flags for TYPO3',
    'description' => 'Add ability to use feature flags for extensions and content elements',
    'category' => 'sys',
    'author' => 'Matthias Gutjahr, Kevin Schu',
    'author_email' => 'dev@aoe.com',
    'author_company' => 'AOE GmbH',
    'shy' => '',
    'dependencies' => 'cms,extbase',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '3.2.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-7.6.99',
            'php' => '5.3.0-0.0.0',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
    'suggests' => array(),
);
