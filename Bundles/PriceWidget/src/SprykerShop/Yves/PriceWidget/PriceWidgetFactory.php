<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\PriceWidget\Dependency\Client\PriceWidgetToPriceClientInterface;
use SprykerShop\Yves\PriceWidget\Dependency\Client\PriceWidgetToPriceStorageClientInterface;
use SprykerShop\Yves\PriceWidget\Dependency\Client\PriceWidgetToQuoteClientInterface;

class PriceWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\PriceWidget\Dependency\Client\PriceWidgetToPriceStorageClientInterface
     */
    public function getPriceStorageClient(): PriceWidgetToPriceStorageClientInterface
    {
        return $this->getProvidedDependency(PriceWidgetDependencyProvider::CLIENT_PRICE_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\PriceWidget\Dependency\Client\PriceWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): PriceWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(PriceWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\PriceWidget\Dependency\Client\PriceWidgetToPriceClientInterface
     */
    public function getPriceClient(): PriceWidgetToPriceClientInterface
    {
        return $this->getProvidedDependency(PriceWidgetDependencyProvider::CLIENT_PRICE);
    }
}
