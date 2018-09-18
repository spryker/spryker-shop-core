<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\CheckoutPage\Plugin;

use Spryker\Shared\CheckoutPermissionConnector\Plugin\Permission\PlaceOrderWithAmountUpToPermissionPlugin as SharedPlaceOrderWithAmountUpToPermissionPlugin;

/**
 * For Client PermissionDependencyProvider::getPermissionPlugins() registration
 *
 * @deprecated Use \Spryker\Shared\CheckoutPermissionConnector\Plugin\PermissionExtension\PlaceOrderWithAmountUpToPermissionPlugin instead
 */
class PlaceOrderWithAmountUpToPermissionPlugin extends SharedPlaceOrderWithAmountUpToPermissionPlugin
{
}
