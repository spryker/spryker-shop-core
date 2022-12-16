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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
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
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     *
     * @return void
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->hasSession()) {
            return;
        }

        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof UserInterface) {
            return;
        }

        $customerTransfer = $this->customerClient->getCustomerByEmail(
            (new CustomerTransfer())->setEmail($user->getUsername()),
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
}
