<?php

$typo3DocumentRoot = getenv('TYPO3_PATH_WEB');

if ($typo3DocumentRoot === false) {
    die(
        'Error: TYPO3 document root not defined. ' . "\n"
            . 'Please set "TYPO3_PATH_WEB" environment variable to TYPO3 root directory '
            . 'and try again.' . "\n"
    );
} else {
    $typo3DocumentRoot = rtrim($typo3DocumentRoot, '/') . '/';
}

require_once $typo3DocumentRoot . 'typo3/sysext/core/Build/UnitTestsBootstrap.php';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['extbase_object'] = array(
    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\NullBackend',
    'options' => array()
);

\TYPO3\CMS\Core\Core\Bootstrap::getInstance()->initializeCachingFramework();