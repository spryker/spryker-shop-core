<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin;

use Symfony\Component\HttpKernel\Event\ControllerEvent;

interface FilterControllerEventHandlerPluginInterface
{
    /**
     * Specification:
     * - Subscribes for symfony ControllerEvent
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     *
     * @return void
     */
    public function handle(ControllerEvent $event): void;
}
