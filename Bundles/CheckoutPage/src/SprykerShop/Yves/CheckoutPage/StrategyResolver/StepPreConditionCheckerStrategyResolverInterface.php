<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PreConditionCheckerInterface;

/**
 * @deprecated Remove strategy resolver after multiple shipment will be released.
 */
interface StepPreConditionCheckerStrategyResolverInterface
{
    public const STRATEGY_KEY_PRE_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_PRE_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT';
    public const STRATEGY_KEY_PRE_CONDITION_CHECKER_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_PRE_CONDITION_CHECKER_WITH_MULTI_SHIPMENT';

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PreConditionCheckerInterface
     */
    public function resolvePreConditionByQuote(QuoteTransfer $quoteTransfer): PreConditionCheckerInterface;
}