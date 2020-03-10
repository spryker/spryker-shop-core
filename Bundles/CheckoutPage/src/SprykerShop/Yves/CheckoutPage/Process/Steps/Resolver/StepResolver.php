<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\Resolver;

use Spryker\Yves\StepEngine\Process\StepCollectionInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;

class StepResolver implements StepResolverInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface[]
     */
    protected $checkoutStepResolverStrategyPlugins;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface[] $checkoutStepResolverStrategyPlugins
     */
    public function __construct(
        CheckoutPageToQuoteClientInterface $quoteClient,
        array $checkoutStepResolverStrategyPlugins
    ) {
        $this->checkoutStepResolverStrategyPlugins = $checkoutStepResolverStrategyPlugins;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[] $steps
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     *
     * @return \Spryker\Yves\StepEngine\Process\StepCollectionInterface
     */
    public function resolveSteps(array $steps, StepCollectionInterface $stepCollection): StepCollectionInterface
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        foreach ($this->checkoutStepResolverStrategyPlugins as $checkoutStepResolverStrategyPlugin) {
            if ($checkoutStepResolverStrategyPlugin->isApplicable($quoteTransfer)) {
                $steps = $checkoutStepResolverStrategyPlugin->execute($steps);
            }
        }

        foreach ($steps as $step) {
            $stepCollection->addStep($step);
        }

        return $stepCollection;
    }
}
