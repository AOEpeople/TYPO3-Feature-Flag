<?php
require_once dirname(__FILE__) . '/../../../../../typo3/sysext/core/Build/UnitTestsBootstrap.php';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['extbase_object'] = array(
    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\NullBackend',
    'options' => array()
);

\TYPO3\CMS\Core\Cache\Cache::flagCachingFrameworkForReinitialization();
\TYPO3\CMS\Core\Cache\Cache::initializeCachingFramework();
