<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductConnector;

use Spryker\Yves\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorFactory as SprykerCmsContentWidgetProductConnectorFactory;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;
use SprykerShop\Yves\ProductWidget\Plugin\CmsContentWidget\ProductGroupWidgetPlugin;
use SprykerShop\Yves\ProductWidget\Plugin\CmsContentWidget\ProductWidgetPlugin;

class CmsContentWidgetProductConnectorFactory extends SprykerCmsContentWidgetProductConnectorFactory
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
    public function getCmsProductContentWidgetPlugins(): array
    {
        // TODO: move to dependency provider
        return [
            // TODO: move to project
            ProductWidgetPlugin::class,
            ProductGroupWidgetPlugin::class,
        ];
    }

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\StorageProductMapperPluginInterface;
     */
    public function getStorageProductMapperPlugin()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductConnectorDependencyProvider::STORAGE_PRODUCT_MAPPER_PLUGIN);
    }

}
