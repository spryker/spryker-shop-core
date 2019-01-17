<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver\AddressStep;

use SprykerShop\Yves\CheckoutPage\StrategyResolver\StepPostConditionCheckerStrategyResolverInterface;
use SprykerShop\Yves\CheckoutPage\StrategyResolver\StepSaverStrategyResolverInterface;

/**
 * @deprecated Remove strategy resolver after multiple shipment will be released.
 */
interface AddressStepStrategyResolverInterface extends StepSaverStrategyResolverInterface,
                                                            StepPostConditionCheckerStrategyResolverInterface
{
}