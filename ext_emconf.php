<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Feature Flags for TYPO3',
    'description' => 'Add ability to use feature flags for extensions and content elements',
    'category' => 'sys',
    'author' => 'Matthias Gutjahr, Kevin Schu',
    'author_email' => 'dev@aoe.com',
    'author_company' => 'AOE GmbH',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '5.1.3',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.7.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
