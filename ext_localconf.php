<?php

use Aoe\FeatureFlag\Form\Element\FeatureFlagBehaviourFormSelectElement;
use Aoe\FeatureFlag\Form\Element\FeatureFlagFormSelectElement;
use Aoe\FeatureFlag\Form\Element\InfoTextElement;

defined('TYPO3') or die();

/**
 * register formEngine-nodes in TYPO3
 */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1634655513] = [
    'nodeName' => 'selectFeatureFlag',
    'priority' => '70',
    'class' => FeatureFlagFormSelectElement::class,
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1634655539] = [
    'nodeName' => 'selectFeatureFlagBehaviour',
    'priority' => '70',
    'class' => FeatureFlagBehaviourFormSelectElement::class,
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1634736513] = [
    'nodeName' => 'infoText',
    'priority' => '70',
    'class' => InfoTextElement::class,
];