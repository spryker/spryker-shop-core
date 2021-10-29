<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\LanguageSwitcherWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client\LanguageSwitcherWidgetToLocaleClientBridge;
use SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client\LanguageSwitcherWidgetToUrlStorageClientBridge;

/**
 * @method \SprykerShop\Yves\LanguageSwitcherWidget\LanguageSwitcherWidgetConfig getConfig()
 */
class LanguageSwitcherWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const CLIENT_URL_STORAGE = 'CLIENT_URL_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->addLocaleClient($container);
        $this->addUrlStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUrlStorageClient(Container $container)
    {
        $container->set(static::CLIENT_URL_STORAGE, function (Container $container) {
            return new LanguageSwitcherWidgetToUrlStorageClientBridge($container->getLocator()->urlStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new LanguageSwitcherWidgetToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }
}
