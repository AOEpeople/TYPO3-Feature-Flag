<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @package FeatureFlag
 */
class Tx_FeatureFlag_TcaPostProcessor
{
    /**
     * Add feature-flag-fields to TCA-fields of DB-tables which support feature-flags
     *
     * @param array $tca
     * @return array
     */
    public function postProcessTca(array $tca)
    {
        $GLOBALS['TCA'] = $tca;

        foreach ($this->getTcaTablesWithFeatureFlagSupport() as $table) {
            ExtensionManagementUtility::addTCAcolumns(
                $table,
                [
                    'tx_featureflag_info' => [
                        'exclude' => 1,
                        'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_info.label',
                        'config' => [
                            'type' => 'user',
                            'userFunc' => 'Tx_FeatureFlag_System_Typo3_TCA->renderInfo',
                        ]
                    ],
                    'tx_featureflag_flag' => [
                        'exclude' => 1,
                        'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_flag',
                        'config' => [
                            'type' => 'user',
                            'userFunc' => 'Tx_FeatureFlag_System_Typo3_TCA->renderSelectForFlag',
                        ]
                    ],
                    'tx_featureflag_behavior' => [
                        'exclude' => 1,
                        'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_behavior',
                        'config' => [
                            'type' => 'user',
                            'userFunc' => 'Tx_FeatureFlag_System_Typo3_TCA->renderSelectForBehavior',
                        ]
                    ]
                ]
            );
            $GLOBALS['TCA'][$table]['palettes']['tx_featureflag'] = ['showitem' => 'tx_featureflag_flag,tx_featureflag_behavior'];
            ExtensionManagementUtility::addToAllTCAtypes(
                $table,
                '--div--;LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:feature_flag,tx_featureflag_info,--palette--;;tx_featureflag'
            );
        }

        $tca = $GLOBALS['TCA'];
        return [$tca];
    }

    /**
     * @return array
     */
    private function getTcaTablesWithFeatureFlagSupport()
    {
        $config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['feature_flag']);
        if (isset($config['tables'])) {
            return explode(',', $config ['tables']);
        }
        return [];
    }
}
