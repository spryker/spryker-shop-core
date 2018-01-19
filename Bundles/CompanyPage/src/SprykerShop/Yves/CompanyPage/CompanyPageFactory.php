<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Plugin\AuthenticationHandlerPluginInterface;
use SprykerShop\Yves\CompanyPage\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CompanyPageFactory extends AbstractFactory
{
    /**
     * @param string $targetUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createRedirectResponse($targetUrl): RedirectResponse
    {
        return new RedirectResponse($targetUrl);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyClientInterface
     */
    public function getCompanyClient(): CompanyPageToCompanyClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Form\FormFactory
     */
    public function createCompanyFormFactory(): FormFactory
    {
        return new FormFactory();
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Plugin\AuthenticationHandlerPluginInterface
     */
    public function getAuthenticationHandler(): AuthenticationHandlerPluginInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::PLUGIN_AUTHENTICATION_HANDLER);
    }

}
