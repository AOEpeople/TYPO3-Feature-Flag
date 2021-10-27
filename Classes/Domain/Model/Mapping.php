<?php
namespace Aoe\FeatureFlag\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Mapping extends AbstractEntity
{
    /**
     * @var string
     */
    protected $tstamp;

    /**
     * @var string
     */
    protected $crdate;

    /**
     * @var \Aoe\FeatureFlag\Domain\Model\FeatureFlag
     */
    protected $featureFlag;

    /**
     * @var int
     */
    protected $foreignTableUid;

    /**
     * @var string
     */
    protected $foreignTableName;

    /**
     * @var int
     */
    protected $foreignTableColumn;

    /**
     * @var string
     */
    protected $behavior;

    /**
     * @param string $crdate
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * @return string
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * @param \Aoe\FeatureFlag\Domain\Model\FeatureFlag $featureFlag
     */
    public function setFeatureFlag(FeatureFlag $featureFlag)
    {
        $this->featureFlag = $featureFlag;
    }

    /**
     * @return \Aoe\FeatureFlag\Domain\Model\FeatureFlag $featureFlag
     */
    public function getFeatureFlag()
    {
        return $this->featureFlag;
    }

    /**
     * @param int $foreignTableColumn
     */
    public function setForeignTableColumn($foreignTableColumn)
    {
        $this->foreignTableColumn = $foreignTableColumn;
    }

    /**
     * @return int
     */
    public function getForeignTableColumn()
    {
        return $this->foreignTableColumn;
    }

    /**
     * @param string $foreignTableName
     */
    public function setForeignTableName($foreignTableName)
    {
        $this->foreignTableName = $foreignTableName;
    }

    /**
     * @return string
     */
    public function getForeignTableName()
    {
        return $this->foreignTableName;
    }

    /**
     * @param int $foreignTableUid
     */
    public function setForeignTableUid($foreignTableUid)
    {
        $this->foreignTableUid = $foreignTableUid;
    }

    /**
     * @return int
     */
    public function getForeignTableUid()
    {
        return $this->foreignTableUid;
    }

    /**
     * @param string $tstamp
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * @return string
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * @param string $behavior
     */
    public function setBehavior($behavior)
    {
        $this->behavior = (int)$behavior;
    }

    /**
     * @return string
     */
    public function getBehavior()
    {
        return (int)$this->behavior;
    }
}
