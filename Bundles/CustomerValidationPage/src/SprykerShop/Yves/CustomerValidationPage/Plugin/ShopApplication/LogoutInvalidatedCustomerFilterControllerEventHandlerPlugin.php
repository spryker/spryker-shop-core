<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerValidationPage\Plugin\ShopApplication;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

/**
 * @method \SprykerShop\Yves\CustomerValidationPage\CustomerValidationPageFactory getFactory()
 */
class LogoutInvalidatedCustomerFilterControllerEventHandlerPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Logs out an invalidated customer.
     * - Customer is considered invalid if the session creation time is earlier than the latest password change or customer was anonymized.
     * - Customer's data is taken from customer_invalidated_storage.
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     *
     * @return void
     */
    public function handle(ControllerEvent $event): void
    {
        $this->getFactory()
            ->createLogoutInvalidatedCustomerFilterControllerEventHandler()
            ->handle($event);
    }
}
