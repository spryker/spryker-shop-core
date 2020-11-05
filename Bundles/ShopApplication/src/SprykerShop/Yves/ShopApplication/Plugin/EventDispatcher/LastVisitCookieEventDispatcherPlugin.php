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
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class LastVisitCookieEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const EVENT_PRIORITY = -255;
    protected const COOKIE_NAME = 'last-visit';
    protected const COOKIE_LIFETIME = 108000;

    /**
     * {@inheritDoc}
     * - Adds a listener to handle cookie insertion with data about last time visit.
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
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (ResponseEvent $event): void {
            $event->getResponse()->headers->setCookie(
                (Cookie::create(static::COOKIE_NAME, (string)time(), time() + static::COOKIE_LIFETIME))
            );
        }, static::EVENT_PRIORITY);

        return $eventDispatcher;
    }
}
