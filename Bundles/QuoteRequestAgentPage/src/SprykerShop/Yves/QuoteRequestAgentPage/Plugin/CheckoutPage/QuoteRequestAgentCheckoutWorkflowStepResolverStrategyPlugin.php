<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 */
class QuoteRequestAgentCheckoutWorkflowStepResolverStrategyPlugin extends AbstractPlugin implements CheckoutStepResolverStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if quote request version is editable by agent.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->getQuoteRequestAgentClient()->isEditableQuoteRequestVersion($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns checkout steps suitable when agent is editing shipment data for request for quote.
     *
     * @api
     *
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[] $steps
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    public function execute(array $steps, QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()->createCheckoutStepResolver()->applyQuoteRequestCheckoutWorkflow($steps);
    }
}
