<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Feature Flags for TYPO3',
    'description' => 'Add ability to use feature flags for extensions and content elements',
    'category' => 'sys',
    'author' => 'Matthias Gutjahr, Kevin Schu',
    'author_email' => 'dev@aoemedia.de',
    'author_company' => 'AOE GmbH',
    'shy' => '',
    'dependencies' => 'cms,extbase',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'alpha',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '0.0.1',
    'constraints' => array(
        'depends' => array(
            'cms' => '',
            'typo3' => '4.5.0-0.0.0',
            'php' => '5.3.0-0.0.0',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
    'suggests' => array(),
);