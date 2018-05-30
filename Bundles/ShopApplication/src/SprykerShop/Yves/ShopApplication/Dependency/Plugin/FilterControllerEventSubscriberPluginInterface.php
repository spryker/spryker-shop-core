<?php

namespace SprykerShop\Yves\ShopApplication\Dependency\Plugin;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

interface FilterControllerEventSubscriberPluginInterface
{
    /**
     * Specification:
     *
     * - Subscribes for symfony FilterControllerEvent
     *
     * @api
     *
     * @param FilterControllerEvent $event
     *
     * @return void
     */
    public function subscribe(FilterControllerEvent $event): void;
}