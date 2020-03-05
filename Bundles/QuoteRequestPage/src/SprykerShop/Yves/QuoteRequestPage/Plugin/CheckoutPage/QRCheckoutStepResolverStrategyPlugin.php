<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QRCheckoutStepResolverStrategyPlugin extends AbstractPlugin implements \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->getQuoteRequestClient()->isQuoteInQuoteRequestProcess($quoteTransfer);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]|\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface[] $steps
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]|\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface[]
     */
    public function execute(array $steps): array
    {
        $resolvedSteps = [
            new \SprykerShop\Yves\QuoteRequestPage\CheckoutStep\EntryStep(
                'cart',
                'quote-request'
            ),
        ];
        $neededSteps = [
            'address',
            'shipment',
        ];
        foreach ($steps as $step) {
            if (!($step instanceof \Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface) || !in_array($step->getCode(), $neededSteps)) {
                continue;
            }

            $resolvedSteps[] = $step;
        }

        $resolvedSteps[] =
            new \SprykerShop\Yves\QuoteRequestPage\CheckoutStep\SaveRFQStep(
                'quote-request/checkout/save',
                'quote-request'
            );

        return $resolvedSteps;
    }
}
