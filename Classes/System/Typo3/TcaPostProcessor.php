<?php

namespace Aoe\FeatureFlag\System\Typo3;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 AOE GmbH <dev@aoe.com>
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

use TYPO3\CMS\Core\Configuration\Event\AfterTcaCompilationEvent;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaPostProcessor
{
    /**
     * Add feature-flag-fields to TCA-fields of DB-tables which support feature-flags
     */
    public function postProcessTca(AfterTcaCompilationEvent $event): void
    {
        foreach ($this->getTcaTablesWithFeatureFlagSupport() as $table) {
            ExtensionManagementUtility::addTCAcolumns(
                $table,
                [
                    'tx_featureflag_info' => [
                        'exclude' => 1,
                        'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:tx_featureflag_info.label',
                        'config' => [
                            'type' => 'user',
                            'renderType' => 'infoText',
                        ],
                    ],
                    'tx_featureflag_flag' => [
                        'exclude' => 1,
                        'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:tx_featureflag_flag',
                        'config' => [
                            'type' => 'user',
                            'renderType' => 'selectFeatureFlag',
                            'size' => 1,
                        ],
                    ],
                    'tx_featureflag_behavior' => [
                        'exclude' => 1,
                        'label' => 'LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:tx_featureflag_behavior',
                        'config' => [
                            'type' => 'user',
                            'renderType' => 'selectFeatureFlagBehaviour',
                            'size' => 1,
                        ],
                    ],
                ]
            );
            ExtensionManagementUtility::addToAllTCAtypes(
                $table,
                '
                    --div--;LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:feature_flag,
                        tx_featureflag_info, tx_featureflag_flag, tx_featureflag_behavior
                '
            );
        }

        $event->setTca($GLOBALS['TCA']);
    }

    private function getTcaTablesWithFeatureFlagSupport(): array
    {
        $config = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('feature_flag');

        if (isset($config['tables'])) {
            return explode(',', $config['tables']);
        }
        return [];
    }
}
