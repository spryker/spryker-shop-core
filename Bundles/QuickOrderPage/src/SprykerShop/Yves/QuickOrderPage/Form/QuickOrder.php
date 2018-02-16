<?php
/**
 * Created by PhpStorm.
 * User: matveyev
 * Date: 2/16/18
 * Time: 10:30
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Generated\Shared\Transfer\QuickOrderItemTransfer;

class QuickOrder
{
    /**
     * @var QuickOrderItemTransfer[]
     */
    protected $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * @return QuickOrderItemTransfer[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param QuickOrderItemTransfer[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
