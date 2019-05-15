<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver\Shipment;

use SprykerShop\Yves\CheckoutPage\Model\Shipment\CreatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface ShipmentCreatorStrategyResolverInterface
{
    public const STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT';
    public const STRATEGY_KEY_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_WITH_MULTI_SHIPMENT';

    /**
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \SprykerShop\Yves\CheckoutPage\Model\Shipment\CreatorInterface
     */
    public function resolve(): CreatorInterface;
}
