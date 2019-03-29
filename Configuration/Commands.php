<?php
return [
    'featureflag:activate' => [
        'class' => \Aoe\FeatureFlag\Command\ActivateFeatureFlagCommand::class
    ],
    'featureflag:deactivate' => [
        'class' => \Aoe\FeatureFlag\Command\DeactivateFeatureFlagCommand::class
    ],
    'featureflag:toggleRecords' => [
        'class' => \Aoe\FeatureFlag\Command\ToggleRecordsCommand::class
    ]
];
