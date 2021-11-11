<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Widget;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountCalculationRequestTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\DiscountPromotionWidget\DiscountPromotionWidgetFactory getFactory()
 */
class CartDiscountPromotionProductListWidget extends AbstractWidget
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     *
     * @var string
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var string
     */
    protected const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    /**
     * @var string
     */
    protected const PARAM_VARIANT_ATTRIBUTES = 'attributes';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(QuoteTransfer $quoteTransfer, Request $request)
    {
        $this
            ->addParameter('cart', $quoteTransfer)
            ->addParameter('promotionProducts', $this->getPromotionProducts($quoteTransfer, $request));
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    protected function getPromotionProducts(QuoteTransfer $quoteTransfer, Request $request): array
    {
        $selectedAttributes = [];
        $productAbstractIds = [];
        $promotionItemTransfersIndexedByProductId = [];
        foreach ($quoteTransfer->getPromotionItems() as $promotionItemTransfer) {
            $productAbstractIds[] = $promotionItemTransfer->getIdProductAbstract();
            $selectedAttributes[$promotionItemTransfer->getIdProductAbstract()] = $this->getSelectedAttributes($request, $promotionItemTransfer->getAbstractSku());
            $promotionItemTransfersIndexedByProductId[$promotionItemTransfer->getIdProductAbstract()] = $promotionItemTransfer;
        }

        $productViewTransfers = $this->getFactory()
            ->getProductStorageClient()
            ->getProductAbstractViewTransfers($productAbstractIds, $this->getLocale(), $selectedAttributes);

        return $this->mapPromotionProducts($productViewTransfers, $promotionItemTransfersIndexedByProductId);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     * @param array<\Generated\Shared\Transfer\PromotionItemTransfer> $promotionItemTransfersIndexedByProductId
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    protected function mapPromotionProducts(array $productViewTransfers, array $promotionItemTransfersIndexedByProductId): array
    {
        $promotionProducts = [];

        foreach ($productViewTransfers as $productViewTransfer) {
            $promotionItemTransfer = $promotionItemTransfersIndexedByProductId[$productViewTransfer->getIdProductAbstract()];

            $productViewTransfer = $this->applyProductViewPriceWithDiscountPromotionalPrice(
                $promotionItemTransfer,
                $productViewTransfer,
            );

            $productViewTransfer->setPromotionItem($promotionItemTransfer);
            $promotionProducts[$this->createPromotionProductBucketIdentifier($promotionItemTransfer)] = $productViewTransfer;
        }

        return $promotionProducts;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $abstractSku
     *
     * @return array
     */
    protected function getSelectedAttributes(Request $request, $abstractSku): array
    {
        /** @var array $selectedAttributes */
        $selectedAttributes = $request->query->get(static::PARAM_VARIANT_ATTRIBUTES) ?: [];

        return isset($selectedAttributes[$abstractSku]) ? array_filter($selectedAttributes[$abstractSku]) : [];
    }

    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     *
     * @return string
     */
    protected function createPromotionProductBucketIdentifier(PromotionItemTransfer $promotionItemTransfer): string
    {
        return $promotionItemTransfer->getAbstractSku() . '-' . $promotionItemTransfer->getIdDiscountPromotion();
    }

    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function applyProductViewPriceWithDiscountPromotionalPrice(
        PromotionItemTransfer $promotionItemTransfer,
        ProductViewTransfer $productViewTransfer
    ): ProductViewTransfer {
        if (empty($productViewTransfer->getPrices()[static::PRICE_TYPE_DEFAULT])) {
            return $productViewTransfer;
        }

        $productViewDefaultPrice = $productViewTransfer->getPrices()[static::PRICE_TYPE_DEFAULT];

        $discountableItemTransfer = (new DiscountableItemTransfer())->setUnitPrice($productViewDefaultPrice);

        $discountCalculationRequestTransfer = (new DiscountCalculationRequestTransfer())
            ->addDiscountableItem($discountableItemTransfer)
            ->setDiscount($promotionItemTransfer->getDiscount());

        $discountCalculationResponseTransfer = $this->getFactory()
            ->getDiscountService()
            ->calculate($discountCalculationRequestTransfer);

        $productViewPrices = [];
        $productViewPrices[static::PRICE_TYPE_ORIGINAL] = $productViewDefaultPrice;
        $productViewPrices[static::PRICE_TYPE_DEFAULT] = $productViewDefaultPrice - $discountCalculationResponseTransfer->getAmount();
        $productViewTransfer->setPrices($productViewPrices);

        return $productViewTransfer;
    }
}
