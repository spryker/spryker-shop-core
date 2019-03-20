<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListSubtotalWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $shoppingListItems
     */
    public function __construct(array $shoppingListItems)
    {
        $this->addSubtotalParameter($shoppingListItems);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListSubtotalWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list-subtotal/shopping-list-subtotal.twig';
    }

    /**
     * @param array $shoppingListItems
     *
     * @return void
     */
    protected function addSubtotalParameter(array $shoppingListItems): void
    {
        $this->addParameter('shoppingListSubtotal', $this->getShoppingListSubtotal($shoppingListItems));
    }

    /**
     * @param array $shoppingListItems
     *
     * @return int
     */
    protected function getShoppingListSubtotal(array $shoppingListItems): int
    {
        $shoppingListItems = $this->prepareShoppingListItems($shoppingListItems);

        return $this->getFactory()
            ->getShoppingListClient()
            ->calculateShoppingListSubtotal($shoppingListItems);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $shoppingListItems
     *
     * @return array
     */
    protected function prepareShoppingListItems(array $shoppingListItems): array
    {
        $preparedShoppingListItems = [];
        foreach ($shoppingListItems as $productViewTransfer) {
            $preparedShoppingListItems[] = [
                ProductViewTransfer::PRICE => $productViewTransfer->getPrice(),
                ProductViewTransfer::QUANTITY => $productViewTransfer->getQuantity(),
            ];
        }

        return $preparedShoppingListItems;
    }
}
