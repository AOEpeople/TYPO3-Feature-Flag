<?php
namespace Aoe\FeatureFlag\Command;

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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ActivateFeatureFlagCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->addArgument(
            'features',
            InputArgument::REQUIRED,
            'comma seperated list of features to activate'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->inputOutput = new SymfonyStyle($input, $output);
        $this->setFeatureStatus($input->getArgument('features'), true);
        return 0;
    }
}
