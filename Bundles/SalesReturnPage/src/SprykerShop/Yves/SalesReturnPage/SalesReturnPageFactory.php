<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface;

class SalesReturnPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface
     */
    public function getSalesReturnClient(): SalesReturnPageToSalesReturnClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_SALES_RETURN);
    }
}
