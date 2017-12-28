<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductSetConnector;

use Spryker\Yves\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorConnectorFactory as SprykerCmsContentWidgetProductSetConnectorConnectorFactory;
use Spryker\Yves\Kernel\Widget\WidgetCollection;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;
use SprykerShop\Yves\CmsContentWidgetProductSetConnector\Dependency\Client\CmsContentWidgetProductSetConnectorToProductSetStorageClientInterface;

class CmsContentWidgetProductSetConnectorFactory extends SprykerCmsContentWidgetProductSetConnectorConnectorFactory
{
    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerRegistry
     */
    public function createWidgetContainerRegistry()
    {
        return new WidgetContainerRegistry();
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    public function createCmsProductSetContentWidgetCollection()
    {
        return new WidgetCollection($this->getCmsProductSetContentWidgetPlugins());
    }

    /**
     * @return string[]
     */
    public function getCmsProductSetContentWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CmsContentWidgetProductSetConnectorDependencyProvider::PLUGIN_CMS_PRODUCT_SET_CONTENT_WIDGETS);
    }

    /**
     * @return CmsContentWidgetProductSetConnectorToProductSetStorageClientInterface
     */
    public function getProductSetStorageClient() : CmsContentWidgetProductSetConnectorToProductSetStorageClientInterface
    {
        return $this->getProvidedDependency(CmsContentWidgetProductSetConnectorDependencyProvider::CLIENT_PRODUCT_SET_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CmsContentWidgetProductSetConnector\Dependency\Client\CmsContentWidgetProductSetConnectorToProductStorageClientInterface
     */
    public function getProductStorageClient()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductSetConnectorDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
