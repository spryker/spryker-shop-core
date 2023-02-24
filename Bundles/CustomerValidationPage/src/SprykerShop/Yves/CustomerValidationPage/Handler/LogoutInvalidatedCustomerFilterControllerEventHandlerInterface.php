<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerValidationPage\Handler;

use Symfony\Component\HttpKernel\Event\ControllerEvent;

interface LogoutInvalidatedCustomerFilterControllerEventHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $controllerEvent
     *
     * @return void
     */
    public function handle(ControllerEvent $controllerEvent): void;
}
