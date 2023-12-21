<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Firewall\AbstractListener;

class ValidateCustomerSessionListener extends AbstractListener implements ValidateCustomerSessionListenerInterface
{
    /**
     * @see \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN
     *
     * @var string
     */
    protected const LOGIN_PATH = '/login';

    /**
     * @var int
     */
    protected const SESSION_INVALIDATE_LIFETIME = 0;

    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface
     */
    protected SessionCustomerValidationPageToCustomerClientInterface $customerClient;

    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface
     */
    protected CustomerSessionValidatorPluginInterface $customerSessionValidatorPlugin;

    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig
     */
    protected SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|null
     */
    protected ?TokenStorageInterface $tokenStorage;

    /**
     * @param \SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface $customerSessionValidatorPlugin
     * @param \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|null $tokenStorage
     */
    public function __construct(
        SessionCustomerValidationPageToCustomerClientInterface $customerClient,
        CustomerSessionValidatorPluginInterface $customerSessionValidatorPlugin,
        SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig,
        ?TokenStorageInterface $tokenStorage = null
    ) {
        $this->customerClient = $customerClient;
        $this->customerSessionValidatorPlugin = $customerSessionValidatorPlugin;
        $this->sessionCustomerValidationPageConfig = $sessionCustomerValidationPageConfig;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return null;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return void
     */
    public function authenticate(RequestEvent $event): void
    {
        if (!$this->isAuthenticated($event)) {
            return;
        }

        $currentCustomer = $this->customerClient->getCustomer();
        if ($currentCustomer === null) {
            return;
        }

        $session = $event->getRequest()->getSession();
        $sessionEntityRequestTransfer = (new SessionEntityRequestTransfer())
            ->setIdEntity($currentCustomer->getIdCustomerOrFail())
            ->setIdSession($session->getId())
            ->setEntityType($this->sessionCustomerValidationPageConfig->getSessionEntityType());

        $sessionEntityResponseTransfer = $this->customerSessionValidatorPlugin->validate($sessionEntityRequestTransfer);
        if ($sessionEntityResponseTransfer->getIsSuccessfull()) {
            return;
        }

        $this->customerClient->logout();
        $session->invalidate(static::SESSION_INVALIDATE_LIFETIME);

        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage */
        $tokenStorage = $this->tokenStorage;
        $tokenStorage->setToken(null);

        $event->setResponse(new RedirectResponse(static::LOGIN_PATH));
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     *
     * @return void
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage): void
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return bool
     */
    protected function isAuthenticated(RequestEvent $event): bool
    {
        if ($this->tokenStorage === null) {
            return false;
        }

        $token = $this->tokenStorage->getToken();

        return $token !== null && $token->getUser() instanceof UserInterface && $event->getRequest()->hasSession();
    }
}
