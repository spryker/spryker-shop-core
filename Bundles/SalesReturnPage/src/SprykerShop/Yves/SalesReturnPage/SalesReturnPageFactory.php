<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToCustomerClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageConfig getConfig()
 */
class SalesReturnPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface
     */
    public function getSalesReturnClient(): SalesReturnPageToSalesReturnClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_SALES_RETURN);
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesClientInterface
     */
    public function getSalesClient(): SalesReturnPageToSalesClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_SALES);
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToCustomerClientInterface
     */
    public function getCustomerClient(): SalesReturnPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\SalesReturnPageConfig
     */
    public function getModuleConfig(): SalesReturnPageConfig
    {
        return $this->getConfig();
    }
}
