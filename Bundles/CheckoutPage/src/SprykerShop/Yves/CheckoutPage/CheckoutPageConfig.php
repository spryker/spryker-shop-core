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
     * @return bool
     */
    public function cleanCartAfterOrderCreation()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getNoShipmentMethodName(): string
    {
        // TODO:
        // - originally this supposed to be \Spryker\Yves\Shipment\ShipmentConfig::getNoShipmentMethodName()
        // - also wrong: \Spryker\Zed\GiftCard\Business\Shipment\ShipmentMethodFilter::NO_SHIPMENT_METHOD
        return 'No shipment';
    }
}
