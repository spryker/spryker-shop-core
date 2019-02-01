<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\StrategyResolver\AddressStep;

use Closure;
use Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\SaverInterface;

/**
 * @deprecated Will be removed in next major release.
 */
class AddressStepStrategyResolver implements AddressStepStrategyResolverInterface
{
    /**
     * @var array|\Closure[]
     */
    protected $strategyContainer;

    /**
     * @param \Closure[] $strategyContainer
     */
    public function __construct(array $strategyContainer)
    {
        $this->strategyContainer = $strategyContainer;
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\SaverInterface
     */
    public function resolveSaver(): SaverInterface
    {
        if (!defined('\Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer::FK_SALES_SHIPMENT')) {
            $this->assertRequiredStrategySaverWithoutMultiShipmentContainerItems();

            return call_user_func($this->strategyContainer[static::STRATEGY_KEY_SAVER_WITHOUT_MULTI_SHIPMENT]);
        }

        $this->assertRequiredStrategySaverWithMultiShipmentContainerItems();

        return call_user_func($this->strategyContainer[static::STRATEGY_KEY_SAVER_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface
     */
    public function resolvePostCondition(): PostConditionCheckerInterface
    {
        if (!defined('\Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer::FK_SALES_SHIPMENT')) {
            $this->assertRequiredStrategyPostConditionCheckerWithoutMultiShipmentContainerItems();

            return call_user_func($this->strategyContainer[static::STRATEGY_KEY_POST_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT]);
        }

        $this->assertRequiredStrategyPostConditionCheckerWithMultiShipmentContainerItems();

        return call_user_func($this->strategyContainer[static::STRATEGY_KEY_POST_CONDITION_CHECKER_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategySaverWithoutMultiShipmentContainerItems(): void
    {
        if (!isset($this->strategyContainer[static::STRATEGY_KEY_SAVER_WITHOUT_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_SAVER_WITHOUT_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_SAVER_WITHOUT_MULTI_SHIPMENT);
        }
    }

    /**
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategySaverWithMultiShipmentContainerItems(): void
    {
        if (!isset($this->strategyContainer[static::STRATEGY_KEY_SAVER_WITH_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_SAVER_WITH_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_SAVER_WITH_MULTI_SHIPMENT);
        }
    }

    /**
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyPostConditionCheckerWithoutMultiShipmentContainerItems(): void
    {
        if (!isset($this->strategyContainer[static::STRATEGY_KEY_POST_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_POST_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_POST_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT);
        }
    }

    /**
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyPostConditionCheckerWithMultiShipmentContainerItems(): void
    {
        if (!isset($this->strategyContainer[static::STRATEGY_KEY_POST_CONDITION_CHECKER_WITH_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_POST_CONDITION_CHECKER_WITH_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_POST_CONDITION_CHECKER_WITH_MULTI_SHIPMENT);
        }
    }
}
