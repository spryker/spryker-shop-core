<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CheckoutPageConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT
     */
    public const SHIPMENT_METHOD_NAME_NO_SHIPMENT = 'NoShipment';

    /**
     * @uses \Spryker\Shared\Nopayment\NopaymentConfig::PAYMENT_PROVIDER_NAME
     */
    public const PAYMENT_METHOD_NAME_NO_PAYMENT = 'Nopayment';

    /**
     * @api
     *
     * @return bool
     */
    public function cleanCartAfterOrderCreation()
    {
        return true;
    }
}
