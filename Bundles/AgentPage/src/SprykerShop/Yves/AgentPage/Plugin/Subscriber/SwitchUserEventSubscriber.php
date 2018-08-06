<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Subscriber;

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
        $customerClient = $this->getFactory()
            ->getCustomerClient();

        if ($targetUser instanceof Customer) {
            $customerClient->setCustomer($targetUser->getCustomerTransfer());
        } elseif ($targetUser instanceof Agent) {
            $customerClient->logout();
        }
    }
}
