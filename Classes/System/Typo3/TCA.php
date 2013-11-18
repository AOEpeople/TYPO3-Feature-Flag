<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 AOE GmbH <dev@aoemedia.de>
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
 * @author Kevin Schu <kevin.schu@aoemedia.de>
 * @author Matthias Gutjahr <matthias.gutjahr@aoemedia.de>
 */
class Tx_FeatureFlag_System_Typo3_TCA
{
    /**
     * @var string
     */
    const FIELD_HIDE = 'tx_featureflag_hide';

    /**
     * @var string
     */
    const FIELD_SHOW = 'tx_featureflag_show';

    /**
     * @var Tx_FeatureFlag_Domain_Repository_FeatureFlag
     */
    protected $featureFlagRepository;

    /**
     * @var Tx_Extbase_Object_ObjectManager
     */
    protected $objectManager;

    /**
     * @var Tx_Extbase_Persistence_Manager
     */
    protected $persistenceManager;

    /**
     * @var Tx_Extbase_Persistence_QueryResultInterface
     */
    protected static $hashedMappings;

    /**
     * @param array $PA
     * @param t3lib_TCEforms $fob
     * @return string
     */
    public function renderSelect(array $PA, t3lib_TCEforms $fob)
    {
        $activeMapping = $this->getMappingRepository()->findByForeignTableNameUidAndColumnName($PA['row']['uid'], $PA['table'], $PA['field']);
        $html = '';
        $html .= '<select id="' . $PA['itemFormElID'] . '" name="' . $PA['itemFormElName'] . '">';
        $html .= '<option value="0"></option>';
        foreach ($this->getFeatureFlagRepository()->findAll() as $featureFlag) {
            /** @var Tx_FeatureFlag_Domain_Model_FeatureFlag $featureFlag */
            $selected = '';
            if ($activeMapping instanceof Tx_FeatureFlag_Domain_Model_Mapping && $activeMapping->getFeatureFlag()->getUid() === $featureFlag->getUid()) {
                $selected = ' selected';
            }
            $value = $featureFlag->getUid();
            $label = $featureFlag->getDescription();
            $html .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Hook for updates in Typo3 backend
     * @param array $incomingFieldArray
     * @param string $table
     * @param integer $id
     * @param t3lib_tcemain $tceMain
     */
    public function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, t3lib_TCEmain &$tceMain)
    {
        $pid = $tceMain->getPID($table, $id);
        $this->updateMapping($table, $id, self::FIELD_HIDE, $incomingFieldArray[self::FIELD_HIDE], $pid);
        $this->updateMapping($table, $id, self::FIELD_SHOW, $incomingFieldArray[self::FIELD_SHOW], $pid);
        unset($incomingFieldArray[self::FIELD_HIDE]);
        unset($incomingFieldArray[self::FIELD_SHOW]);
    }

    /**
     * Hook for deletes in Typo3 Backend. It also delete all overwrite protection
     * @param string $command
     * @param string $table
     * @param integer $id
     */
    public function processCmdmap_postProcess($command, $table, $id)
    {
        if ($command !== 'delete') {
            return;
        }
        $mappings = $this->getMappingRepository()->findByForeignTableNameAndUid($id, $table);
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
            $mapping = $this->getMappingRepository()->findByForeignTableNameAndUid($row['uid'], $table);
            $status['feature_flag_hidden'] = ($mapping->count() > 0 && $row['hidden'] === '1') ? true : false;
            $status['feature_flag'] = ($mapping->count() > 0) ? true : false;
        } else {
            $status['feature_flag_hidden'] = false;
            $status['feature_flag'] = false;
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @param string $field
     * @param int $pid
     * @param int $featureFlag
     */
    protected function updateMapping($table, $id, $field, $featureFlag, $pid)
    {
        $mapping = $this->getMappingRepository()->findByForeignTableNameUidAndColumnName(
            $id,
            $table,
            $field
        );
        if ($mapping instanceof Tx_FeatureFlag_Domain_Model_Mapping) {
            if ('0' === $featureFlag) {
                $this->getMappingRepository()->remove($mapping);
            } else {
                $mapping->setFeatureFlag($this->getFeatureFlagByUid($featureFlag));
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
            $mapping->setForeignTableColumn($field);
            $mapping->setCrdate(time());
            $mapping->setTstamp(time());
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
        if (NULL === self::$hashedMappings) {
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
     * @return Tx_Extbase_Object_ObjectManager
     */
    protected function getObjectManager()
    {
        if (FALSE === ($this->objectManager instanceof Tx_Extbase_Object_ObjectManager)) {
            $this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
        }
        return $this->objectManager;
    }

    /**
     * @return Tx_Extbase_Persistence_Manager
     */
    protected function getPersistenceManager()
    {
        if (FALSE === ($this->persistenceManager instanceof Tx_Extbase_Persistence_Manager)) {
            $this->persistenceManager = $this->getObjectManager()->get('Tx_Extbase_Persistence_Manager');
        }
        return $this->persistenceManager;
    }
}
