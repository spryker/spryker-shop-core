<?php

namespace SprykerShop\Yves\ShopApplication\Dependency\Plugin;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

interface FilterControllerEventHandlerPluginInterface
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
    public function handle(FilterControllerEvent $event): void;
}