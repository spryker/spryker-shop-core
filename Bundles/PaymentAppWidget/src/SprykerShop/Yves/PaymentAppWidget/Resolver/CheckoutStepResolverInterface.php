<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Resolver;

use Generated\Shared\Transfer\QuoteTransfer;

interface CheckoutStepResolverInterface
{
    /**
     * @param list<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface> $steps
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface>
     */
    public function applyExpressCheckoutWorkflow(array $steps, QuoteTransfer $quoteTransfer): array;
}
