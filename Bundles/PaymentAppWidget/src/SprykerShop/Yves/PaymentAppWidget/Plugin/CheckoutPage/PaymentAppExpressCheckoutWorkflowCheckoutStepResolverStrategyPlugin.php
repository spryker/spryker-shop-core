<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 */
class PaymentAppExpressCheckoutWorkflowCheckoutStepResolverStrategyPlugin extends AbstractPlugin implements CheckoutStepResolverStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if the checkout strategy is set to express checkout.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->createExpressCheckoutPaymentChecker()->isExpressCheckoutPayment($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns checkout steps suitable to express checkout workflow.
     * - Cleans quote fields based on the configuration.
     *
     * @api
     *
     * @param list<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface> $steps
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface>
     */
    public function execute(array $steps, QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()->createCheckoutStepResolver()->applyExpressCheckoutWorkflow($steps, $quoteTransfer);
    }
}
