<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\DiscountPromotionWidget\DiscountPromotionItemListWidgetPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\DiscountPromotionWidget\DiscountPromotionWidgetFactory getFactory()
 */
class DiscountPromotionItemListWidgetPlugin extends AbstractWidgetPlugin implements DiscountPromotionItemListWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, Request $request): void
    {
        $this
            ->addParameter('cart', $quoteTransfer)
            ->addParameter('promotionStorageProducts', $this->getPromotionStorageProducts($quoteTransfer, $request));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@DiscountPromotionWidget/_cart-page/item-list.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    protected function getPromotionStorageProducts(QuoteTransfer $quoteTransfer, Request $request): array
    {
        return $this->getFactory()
            ->getProductPromotionMapperPlugin()
            ->mapPromotionItemsFromProductStorage(
                $quoteTransfer,
                $request
            );
    }
}
