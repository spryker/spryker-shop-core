<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Handler;

interface CartOperationInterface
{
    /**
     * @param string $sku
     * @param int $quantity
     * @param array $optionValueUsageIds
     *
     * @return void
     */
    public function add($sku, $quantity, $optionValueUsageIds = []);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function addItems(array $itemTransfers);

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function remove($sku, $groupKey = null);

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function increase($sku, $groupKey = null);

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function decrease($sku, $groupKey = null);

    /**
     * @param string $sku
     * @param int $quantity
     * @param string|null $groupKey
     *
     * @return void
     */
    public function changeQuantity($sku, $quantity, $groupKey = null);
}
