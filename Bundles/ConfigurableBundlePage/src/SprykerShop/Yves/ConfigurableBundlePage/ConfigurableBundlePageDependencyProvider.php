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
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToGlossaryStorageClientBridge;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToPriceProductStorageClientBridge;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToProductImageStorageClientBridge;

class ConfigurableBundlePageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONFIGURABLE_BUNDLE_PAGE_SEARCH = 'CLIENT_CONFIGURABLE_BUNDLE_PAGE_SEARCH';
    public const CLIENT_CONFIGURABLE_BUNDLE_STORAGE = 'CLIENT_CONFIGURABLE_BUNDLE_STORAGE';
    public const CLIENT_CONFIGURABLE_BUNDLE_CART = 'CLIENT_CONFIGURABLE_BUNDLE_CART';
    public const CLIENT_PRODUCT_IMAGE_STORAGE = 'CLIENT_PRODUCT_IMAGE_STORAGE';
    public const CLIENT_PRICE_PRODUCT_STORAGE = 'CLIENT_PRICE_PRODUCT_STORAGE';
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

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
        $container = $this->addProductImageStorageClient($container);
        $container = $this->addPriceProductStorageClient($container);
        $container = $this->addGlossaryStorageClient($container);

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

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductImageStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_IMAGE_STORAGE, function (Container $container) {
            return new ConfigurableBundlePageToProductImageStorageClientBridge(
                $container->getLocator()->productImageStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPriceProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE_PRODUCT_STORAGE, function (Container $container) {
            return new ConfigurableBundlePageToPriceProductStorageClientBridge(
                $container->getLocator()->priceProductStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new ConfigurableBundlePageToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client()
            );
        });

        return $container;
    }
}
