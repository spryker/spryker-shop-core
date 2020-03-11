<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestConvertedCheckoutStepResolverStrategyPlugin extends AbstractPlugin implements CheckoutStepResolverStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if request reference is empty and custom shipment price is set, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->getQuoteRequestClient()
            ->isQuoteRequestForQuoteWithCustomShipmentPrice($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns checkout steps suitable for quote with custom shipment prices.
     *
     * @api
     *
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]|\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface[] $steps
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]|\Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface[]
     */
    public function execute(array $steps): array
    {
        return $this->getFactory()
            ->createCheckoutStepResolver()
            ->resolveCheckoutStepsForQuoteRequestForQuoteWithCustomShipmentPrice($steps);
    }
}
