<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\DiscountPromotionWidget\DiscountPromotionWidgetFactory getFactory()
 */
class CartDiscountPromotionProductListWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_CART = 'cart';

    /**
     * @var string
     */
    protected const PARAMETER_ABSTRACT_SKUS_GROUPED_BY_ID_DISCOUNT = 'abstractSkusGroupedByIdDiscount';

    /**
     * @var string
     */
    protected const PARAMETER_PROMOTION_PRODUCTS = 'promotionProducts';

    /**
     * @var string
     */
    protected const PARAMETER_UNIQUE_DISCOUNTS = 'uniqueDiscounts';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(QuoteTransfer $quoteTransfer, Request $request)
    {
        $this->addCartParameter($quoteTransfer);
        $this->addPromotionProductsParameter($quoteTransfer, $request);
        $this->addAbstractSkusGroupedByIdDiscountParameter($quoteTransfer);
        $this->addUniqueDiscountsParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartDiscountPromotionProductListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@DiscountPromotionWidget/views/cart-discount-promotion-products-list/cart-discount-promotion-products-list.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addCartParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(static::PARAMETER_CART, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function addPromotionProductsParameter(QuoteTransfer $quoteTransfer, Request $request): void
    {
        $promotionProducts = $this->getFactory()
            ->createDiscountPromotionProductReader()
            ->getPromotionProducts($quoteTransfer, $request, $this->getLocale());

        $this->addParameter(static::PARAMETER_PROMOTION_PRODUCTS, $promotionProducts);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addAbstractSkusGroupedByIdDiscountParameter(QuoteTransfer $quoteTransfer): void
    {
        $abstractSkusGroupedByIdDiscount = $this->getFactory()
            ->createDiscountPromotionDiscountReader()
            ->getAbstractSkusGroupedByIdDiscount($quoteTransfer);

        $this->addParameter(
            static::PARAMETER_ABSTRACT_SKUS_GROUPED_BY_ID_DISCOUNT,
            $abstractSkusGroupedByIdDiscount,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addUniqueDiscountsParameter(QuoteTransfer $quoteTransfer): void
    {
        $uniqueDiscounts = $this->getFactory()
            ->createDiscountPromotionDiscountReader()
            ->getUniqueDiscounts($quoteTransfer);

        $this->addParameter(static::PARAMETER_UNIQUE_DISCOUNTS, $uniqueDiscounts);
    }
}
