<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductAbstractWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToContentProductClientBridgeInterface;
use SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToProductStorageClientBridgeInterface;
use SprykerShop\Yves\ContentProductAbstractWidget\Reader\ContentProductAbstractReader;
use SprykerShop\Yves\ContentProductAbstractWidget\Reader\ContentProductAbstractReaderInterface;

class ContentProductAbstractWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ContentProductAbstractWidget\Reader\ContentProductAbstractReaderInterface
     */
    public function createContentProductAbstractReader(): ContentProductAbstractReaderInterface
    {
        return new ContentProductAbstractReader(
            $this->getContentProductClient(),
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToContentProductClientBridgeInterface
     */
    public function getContentProductClient(): ContentProductAbstractWidgetToContentProductClientBridgeInterface
    {
        return $this->getProvidedDependency(ContentProductAbstractWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT);
    }

    /**
     * @return \SprykerShop\Yves\ContentProductAbstractWidget\Dependency\Client\ContentProductAbstractWidgetToProductStorageClientBridgeInterface
     */
    public function getProductStorageClient(): ContentProductAbstractWidgetToProductStorageClientBridgeInterface
    {
        return $this->getProvidedDependency(ContentProductAbstractWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
