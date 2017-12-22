<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\PriceWidget\Dependency\Client\PriceWidgetToPriceStorageClientInterface;

class PriceWidgetFactory extends AbstractFactory
{
    /**
     * @return PriceWidgetToPriceStorageClientInterface
     */
    public function getPriceStorageClient():PriceWidgetToPriceStorageClientInterface
    {
        return $this->getProvidedDependency(PriceWidgetDependencyProvider::CLIENT_PRICE_STORAGE);
    }
}
