<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Subscriber;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentPage\Security\Agent;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class SwitchUserEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const COL_STATUS_ACTIVE = 'active';

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

        $agentUserTransfer = $this->findAgentUserByUsername($this->findAgentUsername($switchUserEvent));

        if ($agentUserTransfer === null) {
            $this->onImpersonationEnd();

            return;
        }

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

    /**
     * @param \Symfony\Component\Security\Http\Event\SwitchUserEvent $switchUserEvent
     *
     * @return string|null
     */
    protected function findAgentUsername(SwitchUserEvent $switchUserEvent): ?string
    {
        $token = $switchUserEvent->getToken();
        if (!$token instanceof SwitchUserToken) {
            return null;
        }

        $originalUser = $token->getOriginalToken()->getUser();
        if (!$originalUser instanceof Agent) {
            return null;
        }

        return $originalUser->getUsername();
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findAgentUserByUsername(string $username): ?UserTransfer
    {
        $userTransfer = new UserTransfer();
        $userTransfer->setUsername($username);

        $userTransfer = $this->getFactory()
            ->getAgentClient()
            ->findAgentByUsername($userTransfer);

        if ($userTransfer && $userTransfer->getStatus() === static::COL_STATUS_ACTIVE) {
            return $userTransfer;
        }

        return null;
    }
}
