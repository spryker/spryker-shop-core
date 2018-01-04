<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductConnector;

use Spryker\Yves\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorDependencyProvider as SprykerCmsContentWidgetProductConnectorDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CmsContentWidgetProductConnector\Dependency\Client\CmsContentWidgetProductConnectorToProductStorageClientBridge;

class CmsContentWidgetProductConnectorDependencyProvider extends SprykerCmsContentWidgetProductConnectorDependencyProvider
{
    const PLUGIN_CMS_PRODUCT_CONTENT_WIDGETS = 'PLUGIN_CMS_PRODUCT_CONTENT_WIDGETS';
    const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCmsProductContentWidgetPlugins($container);
        $container = $this->addProductStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsProductContentWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CMS_PRODUCT_CONTENT_WIDGETS] = function () {
            return $this->getCmsProductContentWidgetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new CmsContentWidgetProductConnectorToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getCmsProductContentWidgetPlugins()
    {
        return [];
    }
}
