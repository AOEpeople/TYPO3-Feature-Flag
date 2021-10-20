<?php
namespace Aoe\FeatureFlag\Form\Element;

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

use Aoe\FeatureFlag\Domain\Model\FeatureFlag;
use Aoe\FeatureFlag\Domain\Model\Mapping;

/**
 * This is rendered for type=user, renderType=selectFeatureFlag
 */
class FeatureFlagFormSelectElement extends AbstractFormSelectElement
{
    /**
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $propertyArray = [
            'table' => $this->data['tableName'],
            'field' => $this->data['fieldName'],
            'row' => $this->data['databaseRow']
        ];

        $activeMapping = $this->mappingRepository->findOneByForeignTableNameAndUid(
            $propertyArray['row']['uid'],
            $propertyArray['table']
        );

        $optionElements = [
            [
                'name' => '',
                'value' => 0,
                'isSelected' => false
            ]
        ];

        foreach ($this->featureFlagRepository->findAll() as $featureFlag) {
            /** @var FeatureFlag $featureFlag */
            $selected = false;
            if ($activeMapping instanceof Mapping &&
                $activeMapping->getFeatureFlag()->getUid() === $featureFlag->getUid()
            ) {
                $selected = true;
            }

            $optionElements[] = [
                'name' => $featureFlag->getDescription(),
                'value' => $featureFlag->getUid(),
                'isSelected' => $selected
            ];
        }

        return $this->renderElement($optionElements);
    }
}
