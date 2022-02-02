<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Resolver;

interface CheckoutStepResolverInterface
{
    /**
     * @param array<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface&\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface> $steps
     *
     * @return array<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface&\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface>
     */
    public function applyQuoteRequestCheckoutWorkflow(array $steps): array;

    /**
     * @param array<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface&\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface> $steps
     *
     * @return array<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface&\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface>
     */
    public function applyQuoteWithCustomShipmentPriceCheckoutWorkflow(array $steps): array;
}
