<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductConnector;

use Spryker\Yves\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorFactory as SprykerCmsContentWidgetProductConnectorFactory;
use Spryker\Yves\Kernel\Widget\WidgetCollection;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;
use SprykerShop\Yves\CmsContentWidgetProductConnector\Dependency\Client\CmsContentWidgetProductConnectorToProductStorageClientInterface;

class CmsContentWidgetProductConnectorFactory extends SprykerCmsContentWidgetProductConnectorFactory
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
    public function createCmsProductContentWidgetCollection()
    {
        return new WidgetCollection($this->getCmsProductContentWidgetPlugins());
    }

    /**
     * @return string[]
     */
    protected function getCmsProductContentWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CmsContentWidgetProductConnectorDependencyProvider::PLUGIN_CMS_PRODUCT_CONTENT_WIDGETS);
    }

    /**
     * @return CmsContentWidgetProductConnectorToProductStorageClientInterface
     */
    public function getProductStorageClient() : CmsContentWidgetProductConnectorToProductStorageClientInterface
    {
        return $this->getProvidedDependency(CmsContentWidgetProductConnectorDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
