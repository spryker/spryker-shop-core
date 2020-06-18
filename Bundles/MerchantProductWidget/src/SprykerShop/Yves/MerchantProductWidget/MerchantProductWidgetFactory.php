<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantProductStorageClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface;

class MerchantProductWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantProductStorageClientInterface
     */
    public function getMerchantProductStorageClient(): MerchantProductWidgetToMerchantProductStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductWidgetDependencyProvider::CLIENT_MERCHANT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantProductWidgetToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductWidgetDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }
}
