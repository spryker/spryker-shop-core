<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

class QuickOrder
{
    /**
     * @var \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $items
     *
     * @return void
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
