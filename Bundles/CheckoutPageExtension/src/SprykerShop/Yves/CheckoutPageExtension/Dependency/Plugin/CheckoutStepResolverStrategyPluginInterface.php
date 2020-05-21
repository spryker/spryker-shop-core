<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Provides configuration capabilities over the checkout process' steps.
 *
 * Use this plugin for adding/removing checkout steps based on the current state of QuoteTransfer.
 */
interface CheckoutStepResolverStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable for steps resolving.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Modifies checkout steps.
     *
     * @api
     *
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[] $steps
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    public function execute(array $steps, QuoteTransfer $quoteTransfer): array;
}
