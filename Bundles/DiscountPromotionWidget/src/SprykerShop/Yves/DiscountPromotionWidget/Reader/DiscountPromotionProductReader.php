<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Reader;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductStorageClientInterface;
use SprykerShop\Yves\DiscountPromotionWidget\Expander\DiscountPromotionProductPriceExpanderInterface;
use Symfony\Component\HttpFoundation\Request;

class DiscountPromotionProductReader implements DiscountPromotionProductReaderInterface
{
    /**
     * @var string
     */
    protected const PARAM_VARIANT_ATTRIBUTES = 'attributes';

    /**
     * @var \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \SprykerShop\Yves\DiscountPromotionWidget\Expander\DiscountPromotionProductPriceExpanderInterface
     */
    protected $discountPromotionProductPriceExpander;

    /**
     * @param \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Client\DiscountPromotionWidgetToProductStorageClientInterface $productStorageClient
     * @param \SprykerShop\Yves\DiscountPromotionWidget\Expander\DiscountPromotionProductPriceExpanderInterface $discountPromotionProductPriceExpander
     */
    public function __construct(
        DiscountPromotionWidgetToProductStorageClientInterface $productStorageClient,
        DiscountPromotionProductPriceExpanderInterface $discountPromotionProductPriceExpander
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->discountPromotionProductPriceExpander = $discountPromotionProductPriceExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $locale
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function getPromotionProducts(QuoteTransfer $quoteTransfer, Request $request, string $locale): array
    {
        $selectedAttributes = [];
        $productAbstractIds = [];
        foreach ($quoteTransfer->getPromotionItems() as $promotionItemTransfer) {
            $idProductAbstract = $promotionItemTransfer->getIdProductAbstractOrFail();

            $productAbstractIds[] = $idProductAbstract;
            $selectedAttributes[$idProductAbstract] = $this->getSelectedAttributes($request, $promotionItemTransfer->getAbstractSkuOrFail());
        }

        $productViewTransfers = $this->productStorageClient
            ->getProductAbstractViewTransfers($productAbstractIds, $locale, $selectedAttributes);

        return $this->mapPromotionProducts($productViewTransfers, $quoteTransfer->getPromotionItems()->getArrayCopy());
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
        $selectedAttributes = $request->query->all()[static::PARAM_VARIANT_ATTRIBUTES] ?? [];

        return isset($selectedAttributes[$abstractSku]) ? array_filter($selectedAttributes[$abstractSku]) : [];
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     * @param array<\Generated\Shared\Transfer\PromotionItemTransfer> $promotionItemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    protected function mapPromotionProducts(array $productViewTransfers, array $promotionItemTransfers): array
    {
        $indexedProductViewTransfers = $this->getProductViewTransfersIndexedByIdProductAbstract($productViewTransfers);

        $promotionProducts = [];
        foreach ($promotionItemTransfers as $promotionItemTransfer) {
            if (!isset($indexedProductViewTransfers[$promotionItemTransfer->getIdProductAbstractOrFail()])) {
                continue;
            }

            $productViewTransfer = (new ProductViewTransfer())
                ->fromArray($indexedProductViewTransfers[$promotionItemTransfer->getIdProductAbstractOrFail()]->toArray());

            $productViewTransfer = $this->discountPromotionProductPriceExpander->expandWithDiscountPromotionalPrice(
                $promotionItemTransfer,
                $productViewTransfer,
            );

            $productViewTransfer->setPromotionItem($promotionItemTransfer);
            $promotionProducts[$this->createPromotionProductBucketIdentifier($promotionItemTransfer)] = $productViewTransfer;
        }

        return $promotionProducts;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    protected function getProductViewTransfersIndexedByIdProductAbstract(array $productViewTransfers): array
    {
        $indexedProductViewTransfers = [];
        foreach ($productViewTransfers as $productViewTransfer) {
            $indexedProductViewTransfers[$productViewTransfer->getIdProductAbstractOrFail()] = $productViewTransfer;
        }

        return $indexedProductViewTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     *
     * @return string
     */
    protected function createPromotionProductBucketIdentifier(PromotionItemTransfer $promotionItemTransfer): string
    {
        return sprintf(
            '%s-%s',
            $promotionItemTransfer->getAbstractSku(),
            $promotionItemTransfer->getIdDiscountPromotion(),
        );
    }
}
