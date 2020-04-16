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
     * @var \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    protected $steps;

    /**
     * @var \Spryker\Yves\StepEngine\Process\StepCollectionInterface
     */
    protected $stepCollection;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface[] $checkoutStepResolverStrategyPlugins
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[] $steps
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     */
    public function __construct(
        CheckoutPageToQuoteClientInterface $quoteClient,
        array $checkoutStepResolverStrategyPlugins,
        array $steps,
        StepCollectionInterface $stepCollection
    ) {
        $this->checkoutStepResolverStrategyPlugins = $checkoutStepResolverStrategyPlugins;
        $this->quoteClient = $quoteClient;
        $this->steps = $steps;
        $this->stepCollection = $stepCollection;
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepCollectionInterface
     */
    public function resolveSteps(): StepCollectionInterface
    {
        $steps = $this->executeCheckoutStepResolverStrategyPlugins($this->steps);

        foreach ($steps as $step) {
            $this->stepCollection->addStep($step);
        }

        return $this->stepCollection;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[] $steps
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    protected function executeCheckoutStepResolverStrategyPlugins(array $steps): array
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        foreach ($this->checkoutStepResolverStrategyPlugins as $checkoutStepResolverStrategyPlugin) {
            if ($checkoutStepResolverStrategyPlugin->isApplicable($quoteTransfer)) {
                $steps = $checkoutStepResolverStrategyPlugin->execute($steps, $quoteTransfer);
            }
        }

        return $steps;
    }
}
