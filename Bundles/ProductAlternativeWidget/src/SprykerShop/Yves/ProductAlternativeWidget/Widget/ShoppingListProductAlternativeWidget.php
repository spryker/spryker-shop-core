<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeWidgetFactory getFactory()
 */
class ShoppingListProductAlternativeWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer, ShoppingListTransfer $shoppingListTransfer)
    {
        $this->addParameter('item', $productViewTransfer)
            ->addParameter('shoppingList', $shoppingListTransfer)
            ->addParameter('products', $this->findAlternativesProducts($productViewTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListProductAlternativeWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductAlternativeWidget/views/shopping-list-product-alternative/shopping-list-product-alternative.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    protected function findAlternativesProducts(ProductViewTransfer $productViewTransfer): array
    {
        $productAlternativeStorageClient = $this->getFactory()->getProductAlternativeStorageClient();

        if (!$productAlternativeStorageClient->isAlternativeProductApplicable($productViewTransfer)) {
            return [];
        }

        return $productAlternativeStorageClient->getConcreteAlternativeProducts($productViewTransfer, $this->getLocale());
    }
}
