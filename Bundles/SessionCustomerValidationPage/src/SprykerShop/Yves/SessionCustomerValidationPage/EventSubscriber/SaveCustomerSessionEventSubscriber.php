<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage\EventSubscriber;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SaveCustomerSessionEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface
     */
    protected SessionCustomerValidationPageToCustomerClientInterface $customerClient;

    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface
     */
    protected CustomerSessionSaverPluginInterface $customerSessionSaverPlugin;

    /**
     * @var \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig
     */
    protected SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig;

    /**
     * @param \SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface $customerSessionSaverPlugin
     * @param \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig
     */
    public function __construct(
        SessionCustomerValidationPageToCustomerClientInterface $customerClient,
        CustomerSessionSaverPluginInterface $customerSessionSaverPlugin,
        SessionCustomerValidationPageConfig $sessionCustomerValidationPageConfig
    ) {
        $this->customerClient = $customerClient;
        $this->customerSessionSaverPlugin = $customerSessionSaverPlugin;
        $this->sessionCustomerValidationPageConfig = $sessionCustomerValidationPageConfig;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        /** @deprecated Exists for Symfony 5 support only. */
        if (!class_exists(LoginSuccessEvent::class)) {
            return [
                SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
            ];
        }

        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     *
     * @return void
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $this->saveSession($event->getRequest(), $event->getAuthenticationToken()->getUser());
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\LoginSuccessEvent $event
     *
     * @return void
     */
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $this->saveSession($event->getRequest(), $event->getUser());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\User\UserInterface|null $user
     *
     * @return void
     */
    protected function saveSession(Request $request, ?UserInterface $user): void
    {
        if (!$request->hasSession()) {
            return;
        }

        if (!$user instanceof UserInterface) {
            return;
        }

        $customerTransfer = $this->customerClient->getCustomerByEmail(
            (new CustomerTransfer())->setEmail($this->getUserIdentifier($user)),
        );

        if (!$customerTransfer->getIdCustomer()) {
            return;
        }

        $this->customerSessionSaverPlugin->saveSession(
            (new SessionEntityRequestTransfer())
                ->setIdEntity($customerTransfer->getIdCustomerOrFail())
                ->setIdSession($request->getSession()->getId())
                ->setEntityType($this->sessionCustomerValidationPageConfig->getSessionEntityType()),
        );
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return string
     */
    protected function getUserIdentifier(UserInterface $user): string
    {
        if ($this->isSymfonyVersion5() === true) {
            return $user->getUsername();
        }

        return $user->getUserIdentifier();
    }

    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return bool
     */
    protected function isSymfonyVersion5(): bool
    {
        return class_exists(AuthenticationProviderManager::class);
    }
}
