<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SwitchUserEventSubscriber implements EventSubscriberInterface
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

        // TODO: if $targetUser is a CustomerTransfer (e.g. impersonation starts), set it to customer session as it would log in
        // TODO: if $targetUser is a UserTransfer (e.g. impersonation ends), unset customer session as it would log out
        // TBD in CC-37
    }
}
