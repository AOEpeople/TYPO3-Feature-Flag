<?php
$extensionPath = t3lib_extMgm::extPath('feature_flag');
$extensionClassesPath = $extensionPath . 'Classes/';
return array(
    'tx_featureflag_domain_model_featureflag' => $extensionClassesPath . 'Domain/Model/FeatureFlag.php',
    'tx_featureflag_domain_model_mapping' => $extensionClassesPath . 'Domain/Model/Mapping.php',
    'tx_featureflag_domain_repository_featureflag' => $extensionClassesPath . 'Domain/Repository/FeatureFlag.php',
    'tx_featureflag_domain_repository_mapping' => $extensionClassesPath . 'Domain/Repository/Mapping.php',
    'tx_featureflag_service' => $extensionClassesPath . 'Service.php',
    'tx_featureflag_service_exception_featurenotfound' => $extensionClassesPath . 'Service/Exception/FeatureNotFound.php',
    'tx_featureflag_system_typo3_configuration' => $extensionClassesPath . 'System/Typo3/Configuration.php',
    'tx_featureflag_system_typo3_task_flagentries' => $extensionClassesPath . 'System/Typo3/Task/FlagEntries.php',
    'tx_featureflag_system_typo3_tca' => $extensionClassesPath . 'System/Typo3/TCA.php',
    'tx_featureflag_system_db_sqlfactory' => $extensionClassesPath . 'System/Db/SqlFactory.php',
    'tx_featureflag_domain_model_featureflagtest' => $extensionPath . 'Tests/Domain/Model/FeatureFlagTest.php',
    'tx_featureflag_domain_model_mappingtest' => $extensionPath . 'Tests/Domain/Model/MappingTest.php',
    'tx_featureflag_domain_repository_featureflagtest' => $extensionPath . 'Tests/Domain/Repository/FeatureFlagTest.php',
    'tx_featureflag_domain_repository_mappingtest' => $extensionPath . 'Tests/Domain/Repository/MappingTest.php',
    'tx_featureflag_system_typo3_configurationtest' => $extensionPath . 'Tests/System/Typo3/ConfigurationTest.php',
    'tx_featureflag_system_typo3_task_flagentriestest' => $extensionPath . 'Tests/System/Typo3/Task/FlagEntriesTest.php',
    'tx_featureflag_system_typo3_tcatest' => $extensionPath . 'Tests/System/Typo3/TCATest.php',
    'tx_featureflag_servicetest' => $extensionPath . 'Tests/ServiceTest.php',
);