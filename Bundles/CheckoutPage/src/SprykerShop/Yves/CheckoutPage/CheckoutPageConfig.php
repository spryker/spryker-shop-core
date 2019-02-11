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
    public function isMultiShipmentManuallyEnabled(): bool
    {
        return true;
    }

    /**
     * @param bool $isMultiShipmentEnabled
     *
     * @return string
     */
    public function getTemplateAddressStep(bool $isMultiShipmentEnabled): string
    {
        if ($isMultiShipmentEnabled) {
            return self::TEMPLATE_MULTI_SHIPMENT_ADDRESS_STEP;
        }

        return self::TEMPLATE_SINGLE_SHIPMENT_ADDRESS_STEP;
    }

    /**
     * @param bool $isMultiShipmentEnabled
     *
     * @return string
     */
    public function getTemplateShipmentStep(bool $isMultiShipmentEnabled): string
    {
        if ($isMultiShipmentEnabled) {
            return self::TEMPLATE_MULTI_SHIPMENT_SHIPMENT_STEP;
        }

        return self::TEMPLATE_SINGLE_SHIPMENT_SHIPMENT_STEP;
    }

    /**
     * @param bool $isMultiShipmentEnabled
     *
     * @return string
     */
    public function getTemplateSummaryStep(bool $isMultiShipmentEnabled): string
    {
        if ($isMultiShipmentEnabled) {
            return self::TEMPLATE_MULTI_SHIPMENT_SUMMARY_STEP;
        }

        return self::TEMPLATE_SINGLE_SHIPMENT_SUMMARY_STEP;
    }
}
