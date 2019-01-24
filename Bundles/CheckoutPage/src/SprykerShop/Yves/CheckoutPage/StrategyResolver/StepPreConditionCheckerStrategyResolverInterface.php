<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver;

use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PreConditionCheckerInterface;

/**
 * @deprecated Will be removed in next major version after multiple shipment release.
 */
interface StepPreConditionCheckerStrategyResolverInterface
{
    public const STRATEGY_KEY_PRE_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_PRE_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT';
    public const STRATEGY_KEY_PRE_CONDITION_CHECKER_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_PRE_CONDITION_CHECKER_WITH_MULTI_SHIPMENT';

    /**
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PreConditionCheckerInterface
     */
    public function resolvePreCondition(): PreConditionCheckerInterface;
}
