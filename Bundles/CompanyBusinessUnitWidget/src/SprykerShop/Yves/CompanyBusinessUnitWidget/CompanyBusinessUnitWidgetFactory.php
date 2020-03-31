<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Form\CompanyBusinessUnitForm;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Form\DataProvider\CompanyBusinessUnitFormDataProvider;
use SprykerShop\Yves\CompanyBusinessUnitWidget\FormHandler\OrderSearchFormHandler;
use SprykerShop\Yves\CompanyBusinessUnitWidget\FormHandler\OrderSearchFormHandlerInterface;
use Symfony\Component\Form\FormTypeInterface;

class CompanyBusinessUnitWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createCompanyBusinessUnitForm(): FormTypeInterface
    {
        return new CompanyBusinessUnitForm();
    }

    /**
     * @return \SprykerShop\Yves\CompanyBusinessUnitWidget\Form\DataProvider\CompanyBusinessUnitFormDataProvider
     */
    public function createCompanyBusinessUnitFormDataProvider(): CompanyBusinessUnitFormDataProvider
    {
        return new CompanyBusinessUnitFormDataProvider(
            $this->getCustomerClient(),
            $this->getCompanyBusinessUnitClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyBusinessUnitWidget\FormHandler\OrderSearchFormHandlerInterface
     */
    public function createOrderSearchFormHandler(): OrderSearchFormHandlerInterface
    {
        return new OrderSearchFormHandler(
            $this->getCustomerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): CompanyBusinessUnitWidgetToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitWidgetDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyBusinessUnitWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
