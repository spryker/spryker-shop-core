<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver\CheckoutStep;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\CheckoutPage\StrategyResolver\MultiShipmentResolverTrait;

/**
 * @deprecated Will be removed in next major release.
 *
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class CheckoutStepTemplateResolver extends AbstractType implements CheckoutStepTemplateResolverInterface
{
    use MultiShipmentResolverTrait;

    /**
     * @return string
     */
    public function getTemplateForAddressStep(): string
    {
        return $this->getConfig()->getTemplateForAddressStep(
            $this->isMultiShipmentEnabled()
        );
    }

    /**
     * @return string
     */
    public function getTemplateForShipmentStep(): string
    {
        return $this->getConfig()->getTemplateForShipmentStep(
            $this->isMultiShipmentEnabled()
        );
    }

    /**
     * @return string
     */
    public function getTemplateForSummaryStep(): string
    {
        return $this->getConfig()->getTemplateForSummaryStep(
            $this->isMultiShipmentEnabled()
        );
    }

    /**
     * @return bool
     */
    public function isMultiShipmentEnabled(): bool
    {
        return $this->isMultiShipmentModuleEnabled() && $this->getConfig()->isMultiShipmentEnabled();
    }
}
