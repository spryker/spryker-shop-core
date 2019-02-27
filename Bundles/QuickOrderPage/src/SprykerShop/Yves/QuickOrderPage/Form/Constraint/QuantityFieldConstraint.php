<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class QuantityFieldConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'quick-order.errors.quantity-required';

    public const OPTION_QUICK_ORDER_SERVICE = 'quickOrderService';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToQuickOrderServiceInterface
     */
    protected $quickOrderService;

    /**
     * @return string|array One or more constant values
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float
    {
        return $this->quickOrderService->round($value);
    }
}
