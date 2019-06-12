<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CheckoutPageConfig extends AbstractBundleConfig
{
    public const TEMPLATE_SINGLE_SHIPMENT_SHIPMENT_STEP = '@CheckoutPage/views/shipment/shipment.twig';
    public const TEMPLATE_MULTI_SHIPMENT_SHIPMENT_STEP = '@CheckoutPage/views/shipment-multi-shipment/shipment-multi-shipment.twig';

    public const TEMPLATE_SINGLE_SHIPMENT_SUMMARY_STEP = '@CheckoutPage/views/summary/summary.twig';
    public const TEMPLATE_MULTI_SHIPMENT_SUMMARY_STEP = '@CheckoutPage/views/summary-multi-shipment/summary-multi-shipment.twig';

    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT
     */
    public const SHIPMENT_METHOD_NAME_NO_SHIPMENT = 'NoShipment';

    /**
     * @uses \Spryker\Shared\Nopayment\NopaymentConfig::PAYMENT_PROVIDER_NAME
     */
    public const PAYMENT_METHOD_NAME_NO_PAYMENT = 'Nopayment';

    /**
     * @return bool
     */
    public function cleanCartAfterOrderCreation()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isMultiShipmentEnabled(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTemplateForShipmentStepWithSingleShipment(): string
    {
        return static::TEMPLATE_SINGLE_SHIPMENT_SHIPMENT_STEP;
    }

    /**
     * @return string
     */
    public function getTemplateForShipmentStepWithMultiShipment(): string
    {
        return static::TEMPLATE_MULTI_SHIPMENT_SHIPMENT_STEP;
    }

    /**
     * @return string
     */
    public function getTemplateForSummaryStepWithSingleShipment(): string
    {
        return static::TEMPLATE_SINGLE_SHIPMENT_SUMMARY_STEP;
    }

    /**
     * @return string
     */
    public function getTemplateForSummaryStepWithMultiShipment(): string
    {
        return static::TEMPLATE_MULTI_SHIPMENT_SUMMARY_STEP;
    }
}
