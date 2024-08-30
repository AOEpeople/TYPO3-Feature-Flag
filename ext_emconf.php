<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Feature Flags for TYPO3',
    'description' => 'Add ability to use feature flags for extensions and content elements',
    'category' => 'sys',
    'author' => 'AOE GmbH',
    'author_email' => 'dev@aoe.com',
    'author_company' => 'AOE GmbH',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '12.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12-4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
