<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantProductStorageClientInterface;

class MerchantProductWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): MerchantProductWidgetToMerchantProductStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductWidgetDependencyProvider::CLIENT_MERCHANT_PRODUCT_STORAGE);
    }
}
