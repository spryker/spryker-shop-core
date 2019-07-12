<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\ControllerResolver\YvesFragmentControllerResolver;
use SprykerShop\Yves\ShopApplication\ControllerResolver\CallbackControllerResolver;
use SprykerShop\Yves\ShopApplication\ControllerResolver\ServiceControllerResolver;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class ShopApplicationApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_RESOLVER = 'resolver';
    protected const DEBUG = 'debug';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addControllerResolver($container);
        $container = $this->addDebugMode($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface $container
     */
    protected function addDebugMode(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::DEBUG, function () {
            return $this->getConfig()->isDebugModeEnabled();
        });

        return $container;
    }

    /**
     * @deprected Use `spryker/router` instead.
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface $container
     */
    protected function addControllerResolver(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_RESOLVER, function (ContainerInterface $container) {
            return new ServiceControllerResolver(
                new YvesFragmentControllerResolver($container),
                new CallbackControllerResolver($container)
            );
        });

        return $container;
    }
}
