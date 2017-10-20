<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductSetConnector;

use Spryker\Yves\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorConnectorFactory as SprykerCmsContentWidgetProductSetConnectorConnectorFactory;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;
use SprykerShop\Yves\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorDependencyProvider;
use SprykerShop\Yves\ProductSetWidget\Plugin\CmsContentWidgetProductSetConnector\ProductSetWidgetPlugin;

class CmsContentWidgetProductSetConnectorFactory extends SprykerCmsContentWidgetProductSetConnectorConnectorFactory
{

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerRegistry
     */
    public function createWidgetContainerRegistry()
    {
        return new WidgetContainerRegistry($this->getApplication());
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getApplication()
    {
        // TODO: move to dependency provider
        return (new Pimple())->getApplication();
    }

    /**
     * @return string[]
     */
    public function getCmsProductSetContentWidgetPlugins(): array
    {
        // TODO: move to dependency provider
        return [
            // TODO: move to project
            ProductSetWidgetPlugin::class,
        ];
    }

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\StorageProductMapperPluginInterface
     */
    public function getStorageMapperPlugin()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductConnectorDependencyProvider::STORAGE_PRODUCT_MAPPER_PLUGIN);
    }

}
