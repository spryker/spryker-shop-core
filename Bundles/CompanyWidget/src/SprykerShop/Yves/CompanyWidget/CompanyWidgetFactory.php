<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface;
use SprykerShop\Yves\CompanyWidget\Widget\DataProvider\CompanyBusinessUnitAddressWidgetDataProvider;

class CompanyWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyWidget\Widget\DataProvider\CompanyBusinessUnitAddressWidgetDataProvider
     */
    public function createCompanyBusinessUnitAddressWidgetDataProvider(): CompanyBusinessUnitAddressWidgetDataProvider
    {
        return new CompanyBusinessUnitAddressWidgetDataProvider();
    }
}
