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
    'version' => '8.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
