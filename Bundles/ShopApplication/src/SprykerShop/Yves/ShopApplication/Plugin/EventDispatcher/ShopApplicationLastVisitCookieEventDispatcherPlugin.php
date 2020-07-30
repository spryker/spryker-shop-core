<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class ShopApplicationLastVisitCookieEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const COOKIES_HANDLER_PRIORITY = -255;
    protected const COOKIES_NAME = 'last-visit';
    protected const COOKIES_VALUE = 'last-visit';
    protected const COOKIES_LIFETIME = 108000;

    /**
     * {@inheritDoc}
     * - Adds a listener to handle cookie insertion for shop application.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (FilterResponseEvent $event): void {
            $event->getResponse()->headers->setCookie(
                (Cookie::create(static::COOKIES_NAME, static::COOKIES_VALUE, time() + static::COOKIES_LIFETIME))
            );
        }, static::COOKIES_HANDLER_PRIORITY);

        return $eventDispatcher;
    }
}
