<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver\StepForm;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\CheckoutPage\StrategyResolver\MultiShipmentResolverTrait;

/**
 * @deprecated Will be removed in next major release.
 *
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class StepFormResolver extends AbstractType implements StepFormResolverInterface
{
    use MultiShipmentResolverTrait;

    /**
     * @return string
     */
    public function getTemplateAddressStep(): string
    {
        return $this->getConfig()->getTemplateAddressStep(
            $this->isMultiShipmentFullEnabled()
        );
    }

    /**
     * @return string
     */
    public function getTemplateShipmentStep(): string
    {
        return $this->getConfig()->getTemplateShipmentStep(
            $this->isMultiShipmentFullEnabled()
        );
    }

    /**
     * @return string
     */
    public function getTemplateSummaryStep(): string
    {
        return $this->getConfig()->getTemplateSummaryStep(
            $this->isMultiShipmentFullEnabled()
        );
    }

    /**
     * @return bool
     */
    protected function isMultiShipmentFullEnabled(): bool
    {
        return $this->isMultiShipmentEnabled() && $this->getConfig()->isMultiShipmentManuallyEnabled();
    }
}
