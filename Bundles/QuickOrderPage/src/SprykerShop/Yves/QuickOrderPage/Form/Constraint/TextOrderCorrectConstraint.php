<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

;

class TextOrderCorrectConstraint extends SymfonyConstraint
{
    public const OPTION_BUNDLE_CONFIG = 'config';

    public $message = 'quick-order.errors.text-order-format-incorrect';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @return array
     */
    public function getAllowedSeparators(): array
    {
        return $this->config->getAllowedSeparators();
    }
}
