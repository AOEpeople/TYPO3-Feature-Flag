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

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use InvalidArgumentException;
use TYPO3\CMS\Core\SingletonInterface;

class Configuration implements SingletonInterface
{
    /**
     * @var string
     */
    const CONF_TABLES = 'tables';

    /**
     * @var array
     */
    private $configuration = [];

    /**
     * @param ExtensionConfiguration $extensionConfiguration
     */
    public function __construct(ExtensionConfiguration $extensionConfiguration)
    {
        $configuration = $extensionConfiguration->get('feature_flag');
        if (is_array($configuration)) {
            $this->configuration = $configuration;
        }
    }

    /**
     * @return array
     */
    public function getTables()
    {
        return explode(',', $this->get(self::CONF_TABLES));
    }

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->configuration)) {
            return $this->configuration[$key];
        }
        throw new InvalidArgumentException('Configuration key "' . $key . '" does not exist.', 1384161387);
    }
}
