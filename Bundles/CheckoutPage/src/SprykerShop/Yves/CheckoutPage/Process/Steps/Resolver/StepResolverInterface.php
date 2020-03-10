<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\Resolver;

use Spryker\Yves\StepEngine\Process\StepCollectionInterface;

interface StepResolverInterface
{
    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[] $steps
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     *
     * @return \Spryker\Yves\StepEngine\Process\StepCollectionInterface
     */
    public function resolveSteps(array $steps, StepCollectionInterface $stepCollection): StepCollectionInterface;
}
