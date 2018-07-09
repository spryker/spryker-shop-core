<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget\Plugin\WishlistPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\WishlistPage\Dependency\Plugin\ProductAlternativeWidget\ProductAlternativeWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeWidgetFactory getFactory()
 */
class ProductAlternativeWidgetPlugin extends AbstractWidgetPlugin implements ProductAlternativeWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $wishlistName
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, string $wishlistName): void
    {
        $this
            ->addParameter('item', $productViewTransfer)
            ->addParameter('wishlistName', $wishlistName)
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
        return '@ProductAlternativeWidget/views/wishlist-product-alternative/wishlist-product-alternative.twig';
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
            ->getConcreteAlternativeProducts($productViewTransfer, $this->getLocale());
    }
}
