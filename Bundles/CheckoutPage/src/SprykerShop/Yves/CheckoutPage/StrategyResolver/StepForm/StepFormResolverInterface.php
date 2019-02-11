<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver\StepForm;

/**
 * @deprecated Will be removed in next major release.
 */
interface StepFormResolverInterface
{
    /**
     * @return string
     */
    public function getTemplateAddressStep(): string;

    /**
     * @return string
     */
    public function getTemplateShipmentStep(): string;

    /**
     * @return string
     */
    public function getTemplateSummaryStep(): string;
}
