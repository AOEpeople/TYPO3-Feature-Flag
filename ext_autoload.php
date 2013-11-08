<?php
$extensionPath = t3lib_extMgm::extPath('feature_flag');
$extensionClassesPath = $extensionPath . 'Classes/';
return array(
    'tx_featureflag_domain_model_featureflag' => $extensionClassesPath . 'Domain/Model/FeatureFlag.php',
    'tx_featureflag_domain_repository_featureflag' => $extensionClassesPath . 'Domain/Repository/FeatureFlag.php',
    'tx_featureflag_service' => $extensionClassesPath . 'Service.php',
    'tx_featureflag_service_exception_featurenotfound' => $extensionClassesPath . 'Service/Exception/FeatureNotFound.php',
    'tx_featureflag_system_typo3_hook_enablefields' => $extensionClassesPath . 'System/Typo3/Hook/EnableFields.php',
    'tx_featureflag_domain_model_featureflagtest' => $extensionPath . 'Tests/Domain/Model/FeatureFlagTest.php',
    'tx_featureflag_servicetest' => $extensionPath . 'Tests/ServiceTest.php',
);