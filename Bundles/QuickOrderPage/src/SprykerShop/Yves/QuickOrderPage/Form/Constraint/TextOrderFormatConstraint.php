<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class TextOrderFormatConstraint extends SymfonyConstraint
{
    /**
     * @var string
     */
    public const OPTION_BUNDLE_CONFIG = 'config';

    /**
     * @var string
     */
    protected const MESSAGE = 'quick-order.paste-order.errors.text-order-format-incorrect';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @return array<string>
     */
    public function getAllowedSeparators(): array
    {
        return $this->config->getTextOrderSeparators();
    }

    /**
     * @return string
     */
    public function getRowSplitterPattern(): string
    {
        return $this->config->getTextOrderRowSplitterPattern();
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }
}
