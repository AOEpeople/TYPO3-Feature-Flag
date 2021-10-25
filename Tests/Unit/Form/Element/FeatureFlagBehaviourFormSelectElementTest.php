<?php
namespace Aoe\FeatureFlag\Tests\Unit\Form\Element;

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

use Aoe\FeatureFlag\Domain\Model\Mapping;
use Aoe\FeatureFlag\Domain\Repository\FeatureFlagRepository;
use Aoe\FeatureFlag\Domain\Repository\MappingRepository;
use Aoe\FeatureFlag\Form\Element\FeatureFlagBehaviourFormSelectElement;
use Aoe\FeatureFlag\Service\FeatureFlagService;
use Aoe\FeatureFlag\Tests\Unit\BaseTest;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FeatureFlagBehaviourFormSelectElementTest extends BaseTest
{
    /**
     * @test
     */
    public function shouldRender()
    {
        $data = [
            'parameterArray' => [
                'fieldConf' => [
                    'config' => [
                        'type' => 'user',
                        'renderType' => 'selectFeatureFlag',
                        'size' => 1,
                    ],
                ],
                'itemFormElValue' => 120,
                'fieldChangeFunc' => [],
            ],
            'tableName' => 'tt_content',
            'field' => 'tx_featureflag_flag',
            'databaseRow' => [
                'uid' => 9999
            ]
        ];

        GeneralUtility::addInstance(IconFactory::class, $this->prophesize(IconFactory::class)->reveal());

        /** @var AbstractNode|ObjectProphecy $abstractNode */
        $abstractNode = $this->prophesize(AbstractNode::class);
        $abstractNode->render(Argument::cetera())->willReturn([
            'additionalJavaScriptPost' => [],
            'additionalJavaScriptSubmit' => [],
            'additionalHiddenFields' => [],
            'stylesheetFiles' => [],
        ]);
        /** @var NodeFactory|ObjectProphecy $nodeFactoryProphecy */
        $nodeFactoryProphecy = $this->prophesize(NodeFactory::class);
        $nodeFactoryProphecy->create(Argument::cetera())->willReturn($abstractNode->reveal());
        $languageService = $this->prophesize(LanguageService::class);
        $languageService->sL(
            'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_behavior.hide'
        )->willReturn('hide');
        $languageService->sL(
            'LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_behavior.show'
        )->willReturn('show');
        $GLOBALS['LANG'] = $languageService->reveal();

        /** @var FeatureFlagRepository|ObjectProphecy */
        $featureFlagRepository = $this->prophesize(FeatureFlagRepository::class);

        /** @var MappingRepository|ObjectProphecy */
        $mappingRepository = $this->prophesize(MappingRepository::class);

        $mapping = new Mapping();
        $mapping->_setProperty('uid', 1000);
        $mapping->setBehavior(FeatureFlagService::BEHAVIOR_SHOW);

        $mappingRepository->findOneByForeignTableNameAndUid(
            9999,
            'tt_content'
        )->willReturn($mapping);

        $subject = new FeatureFlagBehaviourFormSelectElement(
            $nodeFactoryProphecy->reveal(),
            $data,
            $featureFlagRepository->reveal(),
            $mappingRepository->reveal()
        );

        $resultArray = $subject->render();
        self::assertContains('<option value="0">hide</option>', $resultArray['html']);
        self::assertContains(
            '<option selected="selected" value="1">show</option>',
            $resultArray['html']
        );
    }
}
