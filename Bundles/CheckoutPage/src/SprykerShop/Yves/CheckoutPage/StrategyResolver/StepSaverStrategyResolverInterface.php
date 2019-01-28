<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver;

use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\SaverInterface;

/**
 * @deprecated Will be removed in next major release.
 */
interface StepSaverStrategyResolverInterface
{
    public const STRATEGY_KEY_SAVER_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_SAVER_WITHOUT_MULTI_SHIPMENT';
    public const STRATEGY_KEY_SAVER_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_SAVER_WITH_MULTI_SHIPMENT';

    /**
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\SaverInterface
     */
    public function resolveSaver(): SaverInterface;
}
