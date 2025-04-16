<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface CheckoutStepPreConditionPluginInterface
{
    /**
     * Specification:
     * - Executes before the checkout step is processed.
     * - Returns true if the condition is met, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preCondition(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
