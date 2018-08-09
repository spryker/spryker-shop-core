<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Subscriber;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentPage\Security\Agent;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class SwitchUserEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::SWITCH_USER => 'switchUser',
        ];
    }

    /**
     * @param \Symfony\Component\Security\Http\Event\SwitchUserEvent $switchUserEvent
     *
     * @return void
     */
    public function switchUser(SwitchUserEvent $switchUserEvent)
    {
        $targetUser = $switchUserEvent->getTargetUser();

        if ($targetUser instanceof Customer) {
            $this->onImpersonationStart($targetUser);
        } elseif ($targetUser instanceof Agent) {
            $this->onImpersonationEnd();
        }
    }

    /**
     * @param \SprykerShop\Yves\CustomerPage\Security\Customer $customer
     *
     * @return void
     */
    protected function onImpersonationStart(Customer $customer): void
    {
        $this->loginCustomer($customer);
    }

    /**
     * @return void
     */
    protected function onImpersonationEnd(): void
    {
        $this->logoutCustomer();
        $this->clearAgentsQuote();
    }

    /**
     * @param \SprykerShop\Yves\CustomerPage\Security\Customer $customer
     *
     * @return void
     */
    protected function loginCustomer(Customer $customer): void
    {
        $this->getFactory()
            ->getCustomerClient()
            ->setCustomer($customer->getCustomerTransfer());
    }

    /**
     * @return void
     */
    protected function logoutCustomer(): void
    {
        $this->getFactory()
            ->getCustomerClient()
            ->logout();
    }

    /**
     * @return void
     */
    protected function clearAgentsQuote(): void
    {
        $this->getFactory()
            ->getQuoteClient()
            ->setQuote(new QuoteTransfer());
    }
}
