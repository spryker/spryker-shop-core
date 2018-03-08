<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\CheckoutPage\Plugin;

use Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface;

class PlaceOrderWithAmountUpToPermissionPlugin implements ExecutablePermissionPluginInterface
{
    public const KEY = 'PlaceOrderWithAmountUpToPermissionPlugin';

    protected const FIELD_AMOUNT = 'amount';

    /**
     * @param array $configuration
     * @param null $amount
     *
     * @return bool
     */
    public function can(array $configuration, $amount = null): bool
    {
        if (!$amount) {
            return false;
        }

        if ((int)$configuration[static::FIELD_AMOUNT] > (int)$amount) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getConfigurationSignature(): array
    {
        return [
            static::FIELD_AMOUNT => ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_INT
        ];
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return static::KEY;
    }
}
