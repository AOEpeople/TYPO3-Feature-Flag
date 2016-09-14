<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
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
 * @package FeatureFlag
 * @subpackage System_Typo3
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_FeatureFlag_System_Typo3_TCA
{
    /**
     * @var string
     */
    const FIELD_BEHAVIOR = 'tx_featureflag_behavior';

    /**
     * @var string
     */
    const FIELD_FLAG = 'tx_featureflag_flag';

    /**
     * @var Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    protected $featureFlagRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    protected static $hashedMappings;


    /**
     * @param array $PA
     * @return string
     */
    public function renderSelectForFlag(array $PA)
    {
        $activeMapping = $this->getMappingRepository()->findOneByForeignTableNameAndUid($PA['row']['uid'], $PA['table']);

        $html = '';
        $html .= '<select class="select" id="' . $PA['itemFormElID'] . '" name="' . $PA['itemFormElName'] . '">';
        $html .= '<option value="0"></option>';
        foreach ($this->getFeatureFlagRepository()->findAll() as $featureFlag) {
            /** @var Tx_FeatureFlag_Domain_Model_FeatureFlag $featureFlag */
            $selected = '';
            if ($activeMapping instanceof Tx_FeatureFlag_Domain_Model_Mapping &&
                $activeMapping->getFeatureFlag()->getUid() === $featureFlag->getUid()
            ) {
                $selected = ' selected="selected"';
            }
            $value = $featureFlag->getUid();
            $label = $featureFlag->getDescription();
            $html .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
        }
        $html .= '</select>';

        return $html;
    }

    /**
     * @param array $PA
     * @return string
     */
    public function renderInfo(array $PA)
    {
        return $this->getLanguageService()->sL('LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_info.text');
    }

    /**
     * @param array $PA
     * @return string
     */
    public function renderSelectForBehavior(array $PA)
    {
        // check, which behavior is selected
        $isBehaviorHideSelected = false;
        $isBehaviorShowSelected = false;
        $activeMapping = $this->getMappingRepository()->findOneByForeignTableNameAndUid($PA['row']['uid'], $PA['table']);
        if ($activeMapping instanceof Tx_FeatureFlag_Domain_Model_Mapping) {
            if ($activeMapping->getBehavior() === Tx_FeatureFlag_Service::BEHAVIOR_HIDE) {
                $isBehaviorHideSelected = true;
            } elseif ($activeMapping->getBehavior() === Tx_FeatureFlag_Service::BEHAVIOR_SHOW) {
                $isBehaviorShowSelected = true;
            }
        }

        // build select-box
        $html = '';
        $html .= '<select class="select" id="' . $PA['itemFormElID'] . '" name="' . $PA['itemFormElName'] . '">';
        $html .= '<option value="' . Tx_FeatureFlag_Service::BEHAVIOR_HIDE . '"' . ($isBehaviorHideSelected ? ' selected="selected"' : '') .
            '>';
        $html .= $this->getLanguageService()->sL('LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_behavior.hide');
        $html .= '</option>';
        $html .= '<option value="' . Tx_FeatureFlag_Service::BEHAVIOR_SHOW . '"' . ($isBehaviorShowSelected ? ' selected="selected"' : '') .
            '>';
        $html .= $this->getLanguageService()->sL('LLL:EXT:feature_flag/Resources/Private/Language/locallang_db.xml:tx_featureflag_behavior.show');
        $html .= '</option>';
        $html .= '</select>';

        return $html;
    }

    /**
     * Hook for updates in Typo3 backend
     * @param array $incomingFieldArray
     * @param string $table
     * @param integer $id
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $tceMain
     * @codingStandardsIgnoreStart
     */
    public function processDatamap_preProcessFieldArray(
        &$incomingFieldArray,
        $table,
        $id,
        \TYPO3\CMS\Core\DataHandling\DataHandler &$tceMain
    ) {
        // @codingStandardsIgnoreEnd
        if (array_key_exists(self::FIELD_BEHAVIOR, $incomingFieldArray) && array_key_exists(self::FIELD_FLAG, $incomingFieldArray)) {
            $pid = $tceMain->getPID($table, $id);
            $this->updateMapping($table, $id, $incomingFieldArray[self::FIELD_FLAG], $pid, $incomingFieldArray[self::FIELD_BEHAVIOR]);
            unset($incomingFieldArray[self::FIELD_BEHAVIOR]);
            unset($incomingFieldArray[self::FIELD_FLAG]);
        }
    }

    /**
     * Hook for deletes in Typo3 Backend. It also delete all overwrite protection
     * @param string $command
     * @param string $table
     * @param integer $id
     * @codingStandardsIgnoreStart
     */
    public function processCmdmap_postProcess($command, $table, $id)
    {
        // @codingStandardsIgnoreEnd
        if ($command !== 'delete') {
            return;
        }
        $mappings = $this->getMappingRepository()->findAllByForeignTableNameAndUid($id, $table);
        if (false === is_array($mappings) && false === ($mappings instanceof \TYPO3\CMS\Extbase\Persistence\QueryResultInterface)) {
            return;
        }
        foreach ($mappings as $mapping) {
            if ($mapping instanceof Tx_FeatureFlag_Domain_Model_Mapping) {
                $this->getMappingRepository()->remove($mapping);
            }
        }
        $this->getPersistenceManager()->persistAll();
    }

    /**
     * @param string $table
     * @param array $row
     * @param string $status
     */
    public function overrideIconOverlay($table, $row, &$status)
    {
        if ($this->isMappingAvailableForTableAndUid($row['uid'], $table)) {
            $mapping = $this->getMappingRepository()->findOneByForeignTableNameAndUid($row['uid'], $table);
            if ($mapping instanceof Tx_FeatureFlag_Domain_Model_Mapping) {
                $status['feature_flag_hidden'] = ($row['hidden'] === '1') ? true : false;
                $status['feature_flag'] = true;
            } else {
                $status['feature_flag_hidden'] = false;
                $status['feature_flag'] = false;
            }
        } else {
            $status['feature_flag_hidden'] = false;
            $status['feature_flag'] = false;
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @param int $featureFlag
     * @param int $pid
     * @param string $behavior
     */
    protected function updateMapping($table, $id, $featureFlag, $pid, $behavior)
    {
        $mapping = $this->getMappingRepository()->findOneByForeignTableNameAndUid($id, $table);
        if ($mapping instanceof Tx_FeatureFlag_Domain_Model_Mapping) {
            if ('0' === $featureFlag) {
                $this->getMappingRepository()->remove($mapping);
            } else {
                $mapping->setFeatureFlag($this->getFeatureFlagByUid($featureFlag));
                $mapping->setBehavior($behavior);
            }
            $mapping->setTstamp(time());
            $this->getMappingRepository()->update($mapping);
        } elseif ('0' !== $featureFlag) {
            /** @var Tx_FeatureFlag_Domain_Model_Mapping $mapping */
            $mapping = $this->getObjectManager()->get('Tx_FeatureFlag_Domain_Model_Mapping');
            $mapping->setPid($pid);
            $mapping->setFeatureFlag($this->getFeatureFlagByUid($featureFlag));
            $mapping->setForeignTableName($table);
            $mapping->setForeignTableUid($id);
            $mapping->setCrdate(time());
            $mapping->setTstamp(time());
            $mapping->setBehavior($behavior);
            $this->getMappingRepository()->add($mapping);
        }
        $this->getPersistenceManager()->persistAll();
    }

    /**
     * @param int $foreignTableUid
     * @param string $foreignTableName
     * @return bool
     */
    protected function isMappingAvailableForTableAndUid($foreignTableUid, $foreignTableName)
    {
        if (null === self::$hashedMappings) {
            self::$hashedMappings = $this->getMappingRepository()->getHashedMappings();
        }
        $identifier = sha1($foreignTableUid . '_' . $foreignTableName);
        if (array_key_exists($identifier, self::$hashedMappings)) {
            return true;
        }

        return false;
    }

    /**
     * @param int $uid
     * @return Tx_FeatureFlag_Domain_Model_FeatureFlag
     * @throws Tx_FeatureFlag_Service_Exception_FeatureNotFound
     */
    protected function getFeatureFlagByUid($uid)
    {
        /** @var Tx_FeatureFlag_Domain_Model_FeatureFlag $featureFlag */
        $featureFlag = $this->getFeatureFlagRepository()->findByUid($uid);
        if (false === ($featureFlag instanceof Tx_FeatureFlag_Domain_Model_FeatureFlag)) {
            throw new Tx_FeatureFlag_Service_Exception_FeatureNotFound('Feature Flag not found by uid: "' . $uid . '"', 1384340431);
        }

        return $featureFlag;
    }

    /**
     * @return Tx_FeatureFlag_Domain_Repository_Mapping
     */
    protected function getMappingRepository()
    {
        return $this->getObjectManager()->get('Tx_FeatureFlag_Domain_Repository_Mapping');
    }

    /**
     * @return Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    protected function getFeatureFlagRepository()
    {
        return $this->getObjectManager()->get('Tx_FeatureFlag_Domain_Repository_FeatureFlag');
    }

    /**
     * @return \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected function getObjectManager()
    {
        if (false === ($this->objectManager instanceof \TYPO3\CMS\Extbase\Object\ObjectManager)) {
            $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        }

        return $this->objectManager;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected function getPersistenceManager()
    {
        if (false === $this->persistenceManager instanceof \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager) {
            $this->persistenceManager = $this->getObjectManager()->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        }

        return $this->persistenceManager;
    }

    /**
     * @return TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
