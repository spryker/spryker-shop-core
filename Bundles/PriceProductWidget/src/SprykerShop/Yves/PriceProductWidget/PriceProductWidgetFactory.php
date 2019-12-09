<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\PriceProductWidget\Dependency\Client\PriceProductWidgetToPriceProductStorageClientInterface;

class PriceProductWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\PriceProductWidget\Dependency\Client\PriceProductWidgetToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): PriceProductWidgetToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(PriceProductWidgetDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }
}
