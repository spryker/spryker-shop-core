<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopLayout;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShopLayoutExtension\Dependency\Plugin\LanguageSwitcherWidget\LanguageSwitcherWidgetPluginInterface;

class ShopLayoutDependencyProvider extends AbstractBundleDependencyProvider
{
    const STORE = 'STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[self::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @return void
     */
    private function requireExtensions()
    {
        class_exists(LanguageSwitcherWidgetPluginInterface::class);
    }
}
