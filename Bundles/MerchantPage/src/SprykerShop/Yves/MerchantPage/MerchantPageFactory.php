<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\ChainRouter;
use SprykerShop\Yves\MerchantPage\Dependency\Client\MerchantPageToMerchantStorageClientInterface;

class MerchantPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantPage\Dependency\Client\MerchantPageToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantPageToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantPageDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \Spryker\Yves\Router\Router\ChainRouter
     */
    public function getRouter(): ChainRouter
    {
        return $this->getProvidedDependency(MerchantPageDependencyProvider::SERVICE_ROUTER);
    }
}
