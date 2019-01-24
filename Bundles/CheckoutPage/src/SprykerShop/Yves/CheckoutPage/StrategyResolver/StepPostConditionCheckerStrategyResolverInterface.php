<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver;

use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;

/**
 * @deprecated Remove strategy resolver after multiple shipment will be released.
 */
interface StepPostConditionCheckerStrategyResolverInterface
{
    public const STRATEGY_KEY_POST_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_POST_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT';
    public const STRATEGY_KEY_POST_CONDITION_CHECKER_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_POST_CONDITION_CHECKER_WITH_MULTI_SHIPMENT';

    /**
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface
     */
    public function resolvePostCondition(): PostConditionCheckerInterface;
}
