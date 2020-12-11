<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\EventDispatcher;

use Generated\Shared\Transfer\AuthContextTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class FailedLoginMonitorEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addSubscriber(new class implements EventSubscriberInterface {
            protected const FORM_FIELD_SELECTOR_EMAIL = 'loginForm[email]';

            /**
             * @return string[]
             */
            public static function getSubscribedEvents()
            {
                return [
                    AuthenticationEvents::AUTHENTICATION_FAILURE => 'logAuthenticationFailure',
                ];
            }

            /**
             * @return void
             */
            protected function logAuthenticationFailure(): void
            {
                $request = $this->getFactory()->getRequestStack()->getCurrentRequest();

                $authContextTransfer = (new AuthContextTransfer)
                    ->setTtl(0)
                    ->setAccount($request->get(static::FORM_FIELD_SELECTOR_EMAIL))
                    ->setIp($request->getClientIp());

                $this->getClient()->incrementLoginAttempt($authContextTransfer);
            }
        });

        return $eventDispatcher;
    }
}
