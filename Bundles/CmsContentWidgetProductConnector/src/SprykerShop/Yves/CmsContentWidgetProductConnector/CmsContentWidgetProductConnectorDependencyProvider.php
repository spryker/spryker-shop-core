<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductConnector;

use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductDetailPage\Plugin\StorageProductMapperPlugin;
use Spryker\Yves\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorDependencyProvider as SprykerCmsContentWidgetProductConnectorDependencyProvider;

class CmsContentWidgetProductConnectorDependencyProvider extends SprykerCmsContentWidgetProductConnectorDependencyProvider
{

    const STORAGE_PRODUCT_MAPPER_PLUGIN = 'STORAGE_PRODUCT_MAPPER_PLUGIN';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addStorageProductMapperPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStorageProductMapperPlugin(Container $container): Container
    {
        $container[static::STORAGE_PRODUCT_MAPPER_PLUGIN] = function (Container $container) {
            return new StorageProductMapperPlugin();
        };

        return $container;
    }

}
