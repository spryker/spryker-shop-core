<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget\Plugin\ShoppingListPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductAlternativeWidget\ProductAlternativeWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeWidgetFactory getFactory()
 */
class ProductAlternativeWidgetPlugin extends AbstractWidgetPlugin implements ProductAlternativeWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, ShoppingListTransfer $shoppingListTransfer): void
    {
        $this
            ->addParameter('item', $productViewTransfer)
            ->addParameter('shoppingList', $shoppingListTransfer)
            ->addParameter('products', $this->findAlternativesProducts($productViewTransfer));
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductAlternativeWidget/views/shopping-list-product-alternative/shopping-list-product-alternative.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function findAlternativesProducts(ProductViewTransfer $productViewTransfer): array
    {
        if (!$this->getFactory()->getProductAlternativeStorageClient()->isAlternativeProductApplicable($productViewTransfer)) {
            return [];
        }

        return $this->getFactory()->createProductAlternativeMapper()
            ->findConcreteAlternativeProducts($productViewTransfer, $this->getLocale());
    }
}
