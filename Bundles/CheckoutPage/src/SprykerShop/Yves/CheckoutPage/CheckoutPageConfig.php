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
     *
     * @var string
     */
    public const SHIPMENT_METHOD_NAME_NO_SHIPMENT = 'NoShipment';

    /**
     * @uses \Spryker\Shared\Nopayment\NopaymentConfig::PAYMENT_PROVIDER_NAME
     *
     * @var string
     */
    public const PAYMENT_METHOD_NAME_NO_PAYMENT = 'Nopayment';

    /**
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_HOME
     *
     * @var string
     */
    public const ESCAPE_ROUTE = 'home';

    /**
     * @api
     *
     * @return bool
     */
    public function cleanCartAfterOrderCreation()
    {
        return true;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEscapeRoute(): string
    {
        return static::ESCAPE_ROUTE;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getLocalizedTermsAndConditionsPageLinks(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns escape route for success step.
     *
     * @api
     *
     * @return string|null
     */
    public function getSuccessStepEscapeRoute(): ?string
    {
        return null;
    }

    /**
     * Specification:
     * - Returns keys that are used to filter out payment methods during the checkout process on the Payment step.
     *
     * @api
     *
     * @return list<string>
     */
    public function getExcludedPaymentMethodKeys(): array
    {
        return [];
    }
}
