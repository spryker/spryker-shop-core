<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerValidationPage\Handler;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerConditionsTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerStorageClientInterface;
use SprykerShop\Yves\CustomerValidationPage\Validator\CustomerValidationPageValidatorInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class LogoutInvalidatedCustomerFilterControllerEventHandler implements LogoutInvalidatedCustomerFilterControllerEventHandlerInterface
{
    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGOUT
     *
     * @var string
     */
    protected const ROUTE_NAME_LOGOUT = 'logout';

    /**
     * @var \SprykerShop\Yves\CustomerValidationPage\Validator\CustomerValidationPageValidatorInterface
     */
    protected CustomerValidationPageValidatorInterface $customerValidationPageValidator;

    /**
     * @var \SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerStorageClientInterface
     */
    protected CustomerValidationPageToCustomerStorageClientInterface $customerStorageClient;

    /**
     * @var \SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerClientInterface
     */
    protected CustomerValidationPageToCustomerClientInterface $customerClient;

    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected ChainRouterInterface $chainRouter;

    /**
     * @param \SprykerShop\Yves\CustomerValidationPage\Validator\CustomerValidationPageValidatorInterface $customerValidationPageValidator
     * @param \SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerStorageClientInterface $customerStorageClient
     * @param \SprykerShop\Yves\CustomerValidationPage\Dependency\Client\CustomerValidationPageToCustomerClientInterface $customerClient
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $chainRouter
     */
    public function __construct(
        CustomerValidationPageValidatorInterface $customerValidationPageValidator,
        CustomerValidationPageToCustomerStorageClientInterface $customerStorageClient,
        CustomerValidationPageToCustomerClientInterface $customerClient,
        ChainRouterInterface $chainRouter
    ) {
        $this->customerValidationPageValidator = $customerValidationPageValidator;
        $this->customerStorageClient = $customerStorageClient;
        $this->customerClient = $customerClient;
        $this->chainRouter = $chainRouter;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $controllerEvent
     *
     * @return void
     */
    public function handle(ControllerEvent $controllerEvent): void
    {
        $logoutUrl = $this->chainRouter->generate(static::ROUTE_NAME_LOGOUT);
        if ($logoutUrl === $controllerEvent->getRequest()->getRequestUri()) {
            return;
        }

        $customerTransfer = $this->customerClient->getCustomer();
        if ($customerTransfer === null) {
            return;
        }

        $invalidatedCustomerCollectionTransfer = $this->customerStorageClient->getInvalidatedCustomerCollection(
            $this->createInvalidatedCustomerCriteriaTransfer($customerTransfer),
        );

        /**
         * @var \ArrayObject<int, \Generated\Shared\Transfer\InvalidatedCustomerTransfer> $invalidatedCustomers
         */
        $invalidatedCustomers = $invalidatedCustomerCollectionTransfer->getInvalidatedCustomers();
        if (count($invalidatedCustomers) === 0) {
            return;
        }

        $invalidatedCustomerTransfer = $invalidatedCustomers->getIterator()->current();
        if ($this->customerValidationPageValidator->isCustomerValid($invalidatedCustomerTransfer) === false) {
            $this->logout($controllerEvent, $logoutUrl);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer
     */
    protected function createInvalidatedCustomerCriteriaTransfer(
        CustomerTransfer $customerTransfer
    ): InvalidatedCustomerCriteriaTransfer {
        $invalidatedCustomerConditionsTransfer = (new InvalidatedCustomerConditionsTransfer())
            ->addCustomerReference($customerTransfer->getCustomerReferenceOrFail());

        return (new InvalidatedCustomerCriteriaTransfer())
            ->setInvalidatedCustomerConditions($invalidatedCustomerConditionsTransfer);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $controllerEvent
     * @param string $logoutUrl
     *
     * @return void
     */
    protected function logout(ControllerEvent $controllerEvent, string $logoutUrl): void
    {
        $controllerEvent->setController(function () use ($logoutUrl): RedirectResponse {
            return new RedirectResponse($logoutUrl);
        });
    }
}
