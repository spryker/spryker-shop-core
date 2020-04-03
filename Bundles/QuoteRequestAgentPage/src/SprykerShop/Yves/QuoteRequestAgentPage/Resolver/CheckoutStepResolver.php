<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Resolver;

use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithCodeInterface;

class CheckoutStepResolver implements CheckoutStepResolverInterface
{
    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep::STEP_CODE
     */
    protected const ADDRESS_STEP_CODE = 'address';

    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep::STEP_CODE
     */
    protected const SHIPMENT_STEP_CODE = 'shipment';

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    protected $entryStep;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    protected $saveRequestForQuoteStep;

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $entryStep
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $saveRequestForQuoteStep
     */
    public function __construct(StepInterface $entryStep, StepInterface $saveRequestForQuoteStep)
    {
        $this->entryStep = $entryStep;
        $this->saveRequestForQuoteStep = $saveRequestForQuoteStep;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[] $steps
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    public function applyQuoteRequestCheckoutWorkflow(array $steps): array
    {
        $resolvedSteps = [$this->entryStep];

        $neededSteps = [
            static::ADDRESS_STEP_CODE,
            static::SHIPMENT_STEP_CODE,
        ];

        foreach ($steps as $step) {
            if (!$step instanceof StepWithCodeInterface || !in_array($step->getCode(), $neededSteps, true)) {
                continue;
            }

            $resolvedSteps[] = $step;
        }

        $resolvedSteps[] = $this->saveRequestForQuoteStep;

        return $resolvedSteps;
    }
}
