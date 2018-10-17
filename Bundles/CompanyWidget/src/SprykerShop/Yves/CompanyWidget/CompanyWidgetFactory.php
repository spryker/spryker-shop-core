<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyWidget\Address\AddressHandler;
use SprykerShop\Yves\CompanyWidget\Address\AddressHandlerInterface;
use SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface;

class CompanyWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyWidget\Address\AddressHandlerInterface
     */
    public function createAddressHandler(): AddressHandlerInterface
    {
        return new AddressHandler(
            $this->getCustomerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
