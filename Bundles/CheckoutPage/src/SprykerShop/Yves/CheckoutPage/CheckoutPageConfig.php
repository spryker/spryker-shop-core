<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CheckoutPageConfig extends AbstractBundleConfig
{
    public const TEMPLATE_SINGLE_SHIPMENT_ADDRESS_STEP = '@CheckoutPage/views/address/address.twig';
    public const TEMPLATE_MULTI_SHIPMENT_ADDRESS_STEP = '@CheckoutPage/views/address-multi-shipment/address-multi-shipment.twig';

    public const TEMPLATE_SINGLE_SHIPMENT_SHIPMENT_STEP = '@CheckoutPage/views/shipment/shipment.twig';
    public const TEMPLATE_MULTI_SHIPMENT_SHIPMENT_STEP = '@CheckoutPage/views/shipment-multi-shipment/shipment-multi-shipment.twig';

    public const TEMPLATE_SINGLE_SHIPMENT_SUMMARY_STEP = '@CheckoutPage/views/summary/summary.twig';
    public const TEMPLATE_MULTI_SHIPMENT_SUMMARY_STEP = '@CheckoutPage/views/summary-multi-shipment/summary-multi-shipment.twig';

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
    public function getTemplateForAddressStepWithSingleShipment(): string
    {
        return self::TEMPLATE_SINGLE_SHIPMENT_ADDRESS_STEP;
    }

    /**
     * @return string
     */
    public function getTemplateForAddressStepWithMultiShipment(): string
    {
        return self::TEMPLATE_MULTI_SHIPMENT_ADDRESS_STEP;
    }

    /**
     * @return string
     */
    public function getTemplateForShipmentStepWithSingleShipment(): string
    {
        return self::TEMPLATE_SINGLE_SHIPMENT_SHIPMENT_STEP;
    }

    /**
     * @return string
     */
    public function getTemplateForShipmentStepWithMultiShipment(): string
    {
        return self::TEMPLATE_MULTI_SHIPMENT_SHIPMENT_STEP;
    }

    /**
     * @return string
     */
    public function getTemplateForSummaryStepWithSingleShipment(): string
    {
        return self::TEMPLATE_SINGLE_SHIPMENT_SUMMARY_STEP;
    }

    /**
     * @return string
     */
    public function getTemplateForSummaryStepWithMultiShipment(): string
    {
        return self::TEMPLATE_MULTI_SHIPMENT_SUMMARY_STEP;
    }
}
