<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductSetConnector;

use Spryker\Yves\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorDependencyProvider as SprykerCmsContentWidgetProductSetConnectorDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CmsContentWidgetProductSetConnector\Dependency\Client\CmsContentWidgetProductSetConnectorToProductSetStorageClientBridge;
use SprykerShop\Yves\CmsContentWidgetProductSetConnector\Dependency\Client\CmsContentWidgetProductSetConnectorToProductStorageClientBridge;

class CmsContentWidgetProductSetConnectorDependencyProvider extends SprykerCmsContentWidgetProductSetConnectorDependencyProvider
{
    const CLIENT_PRODUCT_SET_STORAGE = 'CLIENT_PRODUCT_SET_STORAGE';
    const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    const PLUGIN_CMS_PRODUCT_SET_CONTENT_WIDGETS = 'PLUGIN_CMS_PRODUCT_SET_CONTENT_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCmsProductSetContentWidgetPlugins($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addProductSetStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsProductSetContentWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CMS_PRODUCT_SET_CONTENT_WIDGETS] = function () {
            return $this->getCmsProductSetContentWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getCmsProductSetContentWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductSetStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_SET_STORAGE] = function (Container $container) {
            return new CmsContentWidgetProductSetConnectorToProductSetStorageClientBridge($container->getLocator()->productSetStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return mixed
     */
    protected function addProductStorageClient(Container $container)
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new CmsContentWidgetProductSetConnectorToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }
}
