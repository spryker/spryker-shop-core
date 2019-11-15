<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleCartClientBridge;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundlePageSearchClientBridge;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientBridge;

class ConfigurableBundlePageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONFIGURABLE_BUNDLE_PAGE_SEARCH = 'CLIENT_CONFIGURABLE_BUNDLE_PAGE_SEARCH';
    public const CLIENT_CONFIGURABLE_BUNDLE_STORAGE = 'CLIENT_CONFIGURABLE_BUNDLE_STORAGE';
    public const CLIENT_CONFIGURABLE_BUNDLE_CART = 'CLIENT_CONFIGURABLE_BUNDLE_CART';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addConfigurableBundlePageSearchClient($container);
        $container = $this->addConfigurableBundleStorageClient($container);
        $container = $this->addConfigurableBundleCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addConfigurableBundlePageSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONFIGURABLE_BUNDLE_PAGE_SEARCH, function (Container $container) {
            return new ConfigurableBundlePageToConfigurableBundlePageSearchClientBridge(
                $container->getLocator()->configurableBundlePageSearch()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addConfigurableBundleStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONFIGURABLE_BUNDLE_STORAGE, function (Container $container) {
            return new ConfigurableBundlePageToConfigurableBundleStorageClientBridge(
                $container->getLocator()->configurableBundleStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addConfigurableBundleCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONFIGURABLE_BUNDLE_CART, function (Container $container) {
            return new ConfigurableBundlePageToConfigurableBundleCartClientBridge(
                $container->getLocator()->configurableBundleCart()->client()
            );
        });

        return $container;
    }
}
