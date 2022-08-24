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
    protected ?string $tstamp = null;

    protected ?string $crdate = null;

    protected ?FeatureFlag $featureFlag = null;

    protected ?int $foreignTableUid = null;

    protected ?string $foreignTableName = null;

    protected ?string $foreignTableColumn = null;

    protected ?string $behavior = null;

    public function setCrdate(string $crdate): void
    {
        $this->crdate = $crdate;
    }

    public function getCrdate(): ?string
    {
        return $this->crdate;
    }

    public function setFeatureFlag(FeatureFlag $featureFlag): void
    {
        $this->featureFlag = $featureFlag;
    }

    public function getFeatureFlag(): ?FeatureFlag
    {
        return $this->featureFlag;
    }

    public function setForeignTableColumn(string $foreignTableColumn): void
    {
        $this->foreignTableColumn = $foreignTableColumn;
    }

    public function getForeignTableColumn(): ?string
    {
        return $this->foreignTableColumn;
    }

    public function setForeignTableName(string $foreignTableName): void
    {
        $this->foreignTableName = $foreignTableName;
    }

    public function getForeignTableName(): ?string
    {
        return $this->foreignTableName;
    }

    public function setForeignTableUid(string $foreignTableUid): void
    {
        $this->foreignTableUid = $foreignTableUid;
    }

    public function getForeignTableUid(): ?string
    {
        return $this->foreignTableUid;
    }

    public function setTstamp(string $tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    public function getTstamp(): ?string
    {
        return $this->tstamp;
    }

    public function setBehavior(string $behavior): void
    {
        $this->behavior = $behavior;
    }

    public function getBehavior(): int
    {
        return (int) $this->behavior;
    }
}
