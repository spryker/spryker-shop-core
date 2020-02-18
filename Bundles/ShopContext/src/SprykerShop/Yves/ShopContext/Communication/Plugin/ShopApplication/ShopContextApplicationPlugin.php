<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopContext\Communication\Plugin\ShopApplication;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\ShopContext\ShopContextFactory getFactory()
 */
class ShopContextApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_SHOP_CONTEXT = 'shop_context';

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
        $container->set(static::SERVICE_SHOP_CONTEXT, $container->factory(function ($container) {
            return $this->getFactory()
                ->createShopContextProvider()
                ->provide();
        }));

        return $container;
    }
}
