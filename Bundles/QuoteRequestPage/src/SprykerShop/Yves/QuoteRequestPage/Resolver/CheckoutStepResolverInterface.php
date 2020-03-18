<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Resolver;

interface CheckoutStepResolverInterface
{
    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[] $steps
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    public function resolveCheckoutStepsForQuoteInQuoteRequestProcess(array $steps): array;

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[] $steps
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    public function resolveCheckoutStepsForQuoteRequestForQuoteWithCustomShipmentPrice(array $steps): array;
}
