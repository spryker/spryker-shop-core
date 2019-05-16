<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver\CheckoutStep;

use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class CheckoutStepTemplateResolver implements CheckoutStepTemplateResolverInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig
     */
    protected $checkoutPageConfig;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     */
    public function __construct(CheckoutPageConfig $checkoutPageConfig)
    {
        $this->checkoutPageConfig = $checkoutPageConfig;
    }

    /**
     * @return string
     */
    public function getTemplateForAddressStep(): string
    {
        if ($this->checkoutPageConfig->isMultiShipmentEnabled()) {
            return $this->checkoutPageConfig->getTemplateForAddressStepWithMultiShipment();
        }

        return $this->checkoutPageConfig->getTemplateForAddressStepWithSingleShipment();
    }

    /**
     * @return string
     */
    public function getTemplateForShipmentStep(): string
    {
        if ($this->checkoutPageConfig->isMultiShipmentEnabled()) {
            return $this->checkoutPageConfig->getTemplateForShipmentStepWithMultiShipment();
        }

        return $this->checkoutPageConfig->getTemplateForShipmentStepWithSingleShipment();
    }

    /**
     * @return string
     */
    public function getTemplateForSummaryStep(): string
    {
        if ($this->checkoutPageConfig->isMultiShipmentEnabled()) {
            return $this->checkoutPageConfig->getTemplateForSummaryStepWithMultiShipment();
        }

        return $this->checkoutPageConfig->getTemplateForSummaryStepWithSingleShipment();
    }
}
