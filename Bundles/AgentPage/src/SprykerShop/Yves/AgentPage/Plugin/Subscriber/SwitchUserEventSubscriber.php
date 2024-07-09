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
     * @return array<string, mixed>
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

            $this->getFactory()->createAuditLogger()->addImpersonationStartedAuditLog();
        } elseif ($targetUser instanceof Agent) {
            $this->onImpersonationEnd();

            $this->getFactory()->createAuditLogger()->addImpersonationEndedAuditLog();
        }
    }

    /**
     * @param \SprykerShop\Yves\CustomerPage\Security\Customer $customer
     *
     * @return void
     */
    protected function onImpersonationStart(Customer $customer): void
    {
        $this->getFactory()
            ->createSessionImpersonator()
            ->impersonate($customer);
    }

    /**
     * @return void
     */
    protected function onImpersonationEnd(): void
    {
        $this->getFactory()->getAgentClient()->finishImpersonationSession();
        $this->clearAgentsQuote();
    }

    /**
     * @deprecated Use {@link \Spryker\Client\Quote\Plugin\Agent\SanitizeCustomerQuoteImpersonationSessionFinisherPlugin::finish()} instead.
     *
     * @return void
     */
    protected function clearAgentsQuote(): void
    {
        $this->getFactory()
            ->getQuoteClient()
            ->setQuote(new QuoteTransfer());
    }
}
