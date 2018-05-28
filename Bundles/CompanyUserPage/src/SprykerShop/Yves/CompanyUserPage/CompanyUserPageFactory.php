<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyUserPage\Dependency\CompanyPageToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\CompanyUserPage\Form\DataProvider\CompanyUserAccountDataProvider;
use SprykerShop\Yves\CompanyUserPage\Form\FormFactory;

class CompanyUserPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyUserPage\Form\FormFactory
     */
    public function createCompanyUserPageFormFactory()
    {
        return new FormFactory();
    }

    /**
     * @return CompanyUserAccountDataProvider
     */
    public function createCompanyUserAccountDataProvider(): CompanyUserAccountDataProvider
    {
        return new CompanyUserAccountDataProvider(
            $this->getBusinessOnBehalfClient()
        );
    }

    /**
     * @return CompanyPageToBusinessOnBehalfClientInterface
     */
    protected function getBusinessOnBehalfClient(): CompanyPageToBusinessOnBehalfClientInterface
    {
        return $this->getProvidedDependency(CompanyUserPageDependencyProvider::CLIENT_BUSINESS_ON_BEHALF);
    }
}
