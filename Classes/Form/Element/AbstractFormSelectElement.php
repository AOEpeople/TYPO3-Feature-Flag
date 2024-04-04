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

use Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository;
use Aoe\FeatureFlag\Domain\Repository\MappingRepository;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

abstract class AbstractFormSelectElement extends AbstractFormElement
{
    protected object $featureFlagRepository;

    protected object $mappingRepository;

    /**
     * Container objects give $nodeFactory down to other containers.
     */
    public function __construct(
        NodeFactory $nodeFactory,
        array $data,
        FeatureFlagRepository $featureFlagRepository = null,
        MappingRepository $mappingRepository = null
    ) {
        parent::__construct($nodeFactory, $data);

        $this->featureFlagRepository = $featureFlagRepository ??
            GeneralUtility::makeInstance(FeatureFlagRepository::class);

        $this->mappingRepository = $mappingRepository ??
            GeneralUtility::makeInstance(MappingRepository::class);
    }

    /**
     * This will render a selector box element, or possibly a special construction with two selector boxes.
     */
    public function renderElement(array $optionElements): array
    {
        $resultArray = $this->initializeResultArray();
        $parameterArray = $this->data['parameterArray'];

        // Field configuration from TCA:
        $config = $parameterArray['fieldConf']['config'];

        $selectElement = $this->renderSelectElement($optionElements, $parameterArray, $config);

        $width = MathUtility::forceIntegerInRange(
            $config['width'] ?? $this->defaultInputWidth,
            $this->minimumInputWidth,
            $this->maxInputWidth
        );
        $maxWidth = $this->formMaxWidth($width);

        $html = [];
        $html[] = '<div class="formengine-field-item t3js-formengine-field-item">';
        $html[] = '<div class="form-control-wrap" style="max-width: ' . $maxWidth . 'px">';
        $html[] = '<div class="form-wizards-wrap">';
        $html[] = '<div class="form-wizards-element">';
        $html[] = $selectElement;
        $html[] = '</div>';
        $html[] = '</div>';
        $html[] = '</div>';

        $resultArray['html'] = implode(LF, $html);

        return $resultArray;
    }

    /**
     * Renders a <select> element
     */
    protected function renderSelectElement(
        array $options,
        array $parameterArray,
        array $config
    ): string {
        $attributes = [
            'id' => StringUtility::getUniqueId('tceforms-select-'),
            'name' => $parameterArray['itemFormElName'],
            'class' => 'form-select tceforms-select',
            'data-formengine-validation-rules' => $this->getValidationDataAsJsonString($config),
            'disabled' => (string) !empty($config['readOnly']),
            'size' => $config['size'],
        ];

        $optionElements = [];
        foreach ($options as $option) {
            $optionAttributes = [];
            if ($option['isSelected']) {
                $optionAttributes['selected'] = 'selected';
            }

            $optionElements[] = $this->renderOptionElement($option['value'], $option['name'], $optionAttributes);
        }

        $html = [];
        $html[] = '<select ' . GeneralUtility::implodeAttributes($attributes, true) . '>';
        $html[] = implode(LF, $optionElements);
        $html[] = '</select>';

        return implode(LF, $html);
    }

    /**
     * Renders a single <option> element
     */
    protected function renderOptionElement(string $value, string $label, array $attributes = []): string
    {
        $attributes['value'] = $value;
        $html = [
            '<option ' . GeneralUtility::implodeAttributes($attributes, true) . '>',
            htmlspecialchars($label, ENT_COMPAT, 'UTF-8', false),
            '</option>',

        ];

        return implode('', $html);
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
