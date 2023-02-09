<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerValidationPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerStorageClientInterface;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToSessionClientInterface;
use SprykerShop\Yves\CustomerValidationPage\Handler\LogoutInvalidatedCustomerFilterControllerEventHandler;
use SprykerShop\Yves\CustomerValidationPage\Handler\LogoutInvalidatedCustomerFilterControllerEventHandlerInterface;
use SprykerShop\Yves\CustomerValidationPage\Validator\CustomerValidationPageValidator;
use SprykerShop\Yves\CustomerValidationPage\Validator\CustomerValidationPageValidatorInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

class CustomerValidationPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CustomerValidationPage\Handler\LogoutInvalidatedCustomerFilterControllerEventHandlerInterface
     */
    public function createLogoutInvalidatedCustomerFilterControllerEventHandler(): LogoutInvalidatedCustomerFilterControllerEventHandlerInterface
    {
        return new LogoutInvalidatedCustomerFilterControllerEventHandler(
            $this->createCustomerValidationPageValidator(),
            $this->getCustomerStorageClient(),
            $this->getCustomerClient(),
            $this->getRouter(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerValidationPage\Validator\CustomerValidationPageValidatorInterface
     */
    public function createCustomerValidationPageValidator(): CustomerValidationPageValidatorInterface
    {
        return new CustomerValidationPageValidator(
            $this->getSessionClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerStorageClientInterface
     */
    public function getCustomerStorageClient(): CustomerValidationPageToCustomerStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerValidationPageDependencyProvider::CLIENT_CUSTOMER_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerClientInterface
     */
    public function getCustomerClient(): CustomerValidationPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomerValidationPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToSessionClientInterface
     */
    public function getSessionClient(): CustomerValidationPageToSessionClientInterface
    {
        return $this->getProvidedDependency(CustomerValidationPageDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public function getRouter(): ChainRouterInterface
    {
        return $this->getProvidedDependency(CustomerValidationPageDependencyProvider::SERVICE_ROUTER);
    }
}
