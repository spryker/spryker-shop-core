<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Subscriber;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class InteractiveLoginEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $interactiveLoginEvent
     *
     * @return void
     */
    public function onInteractiveLogin(InteractiveLoginEvent $interactiveLoginEvent)
    {
        /** @var \SprykerShop\Yves\CustomerPage\Security\Customer $customer */
        $customer = $interactiveLoginEvent->getAuthenticationToken()->getUser();
        if ($customer instanceof Customer) {
            $this->setCustomerSession($customer->getCustomerTransfer());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function setCustomerSession(CustomerTransfer $customerTransfer)
    {
        $this->getFactory()
            ->getCustomerClient()
            ->setCustomer($customerTransfer);
    }
}
