<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Zed\DateTimeConfiguratorPageExample\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \SprykerShop\Zed\DateTimeConfiguratorPageExample\Business\DateTimeConfiguratorPageExampleFacadeInterface getFacade()
 */
class DateTimeProductConfiguratorBuildFrontendConsole extends Console
{
    public const COMMAND_NAME = 'frontend:date-time-product-configurator:build';
    public const DESCRIPTION = 'This command will build Product Configurator frontend.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->info('Build Product Configurator frontend');

        if ($this->getFacade()->buildProductConfigurationFrontend($this->getMessenger())) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }
}
