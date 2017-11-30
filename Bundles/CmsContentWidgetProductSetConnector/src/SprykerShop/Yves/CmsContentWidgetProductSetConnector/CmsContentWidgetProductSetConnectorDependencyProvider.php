<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductSetConnector;

use Spryker\Yves\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorDependencyProvider as SprykerCmsContentWidgetProductSetConnectorDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\ProductDetailPage\Plugin\StorageProductMapperPlugin;

class CmsContentWidgetProductSetConnectorDependencyProvider extends SprykerCmsContentWidgetProductSetConnectorDependencyProvider
{
    const PLUGIN_STORAGE_PRODUCT_MAPPER = 'PLUGIN_STORAGE_PRODUCT_MAPPER';
    const APPLICATION = 'APPLICATION';
    const PLUGIN_CMS_PRODUCT_SET_CONTENT_WIDGETS = 'PLUGIN_CMS_PRODUCT_SET_CONTENT_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addApplication($container);
        $container = $this->addStorageProductMapperPlugin($container);
        $container = $this->addCmsProductSetContentWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container[static::APPLICATION] = function (Container $container) {
            return (new Pimple())->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStorageProductMapperPlugin(Container $container): Container
    {
        $container[static::PLUGIN_STORAGE_PRODUCT_MAPPER] = function (Container $container) {
            return new StorageProductMapperPlugin(); // TODO: invalid plugin usage
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsProductSetContentWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CMS_PRODUCT_SET_CONTENT_WIDGETS] = function (Container $container) {
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
}
