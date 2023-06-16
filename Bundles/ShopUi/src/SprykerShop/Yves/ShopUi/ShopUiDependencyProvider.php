<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToLocaleClientBridge;
use SprykerShop\Yves\ShopUi\Dependency\Client\ShopUiToTwigClientBridge;
use SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilNumberServiceBridge;
use SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilSanitizeXssServiceBridge;

/**
 * @method \SprykerShop\Yves\ShopUi\ShopUiConfig getConfig()
 */
class ShopUiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_TWIG = 'CLIENT_TWIG';

    /**
     * @var string
     */
    public const SERVICE_UTIL_NUMBER = 'SERVICE_UTIL_NUMBER';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SANITIZE_XSS = 'SERVICE_UTIL_SANITIZE_XSS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addTwigClient($container);
        $container = $this->addUtilNumberService($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addUtilSanitizeXssService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigClient(Container $container): Container
    {
        $container->set(static::CLIENT_TWIG, function (Container $container) {
            return new ShopUiToTwigClientBridge(
                $container->getLocator()->twig()->client(),
            );
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
            return new ShopUiToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilNumberService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_NUMBER, function (Container $container) {
            return new ShopUiToUtilNumberServiceBridge($container->getLocator()->utilNumber()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilSanitizeXssService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_SANITIZE_XSS, function (Container $container) {
            return new ShopUiToUtilSanitizeXssServiceBridge(
                $container->getLocator()->utilSanitizeXss()->service(),
            );
        });

        return $container;
    }
}
