<?php

namespace Aoe\FeatureFlag\Form\Element;

use Aoe\FeatureFlag\Domain\Model\Mapping;
use Aoe\FeatureFlag\Service\FeatureFlagService;

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

/**
 * This is rendered for type=user, renderType=selectFeatureFlagBehaviour
 */
class FeatureFlagBehaviourFormSelectElement extends AbstractFormSelectElement
{
    public function render(): array
    {
        $propertyArray = [
            'table' => $this->data['tableName'],
            'field' => $this->data['fieldName'],
            'row' => $this->data['databaseRow'],
        ];

        // check, which behavior is selected
        $isBehaviorHideSelected = false;
        $isBehaviorShowSelected = false;
        $activeMapping = $this->mappingRepository->findOneByForeignTableNameAndUid(
            $propertyArray['row']['uid'],
            $propertyArray['table']
        );
        if ($activeMapping instanceof Mapping) {
            if ($activeMapping->getBehavior() === FeatureFlagService::BEHAVIOR_HIDE) {
                $isBehaviorHideSelected = true;
            } elseif ($activeMapping->getBehavior() === FeatureFlagService::BEHAVIOR_SHOW) {
                $isBehaviorShowSelected = true;
            }
        }

        $optionElements = [
            [
                'name' => $this->getLanguageService()
                    ->sL('LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:tx_featureflag_behavior.hide'),
                'value' => FeatureFlagService::BEHAVIOR_HIDE,
                'isSelected' => $isBehaviorHideSelected,
            ],
            [
                'name' => $this->getLanguageService()
                    ->sL('LLL:EXT:feature_flag/Resources/Private/Language/locallang.xlf:tx_featureflag_behavior.show'),
                'value' => FeatureFlagService::BEHAVIOR_SHOW,
                'isSelected' => $isBehaviorShowSelected,
            ],
        ];

        return $this->renderElement($optionElements);
    }
}
