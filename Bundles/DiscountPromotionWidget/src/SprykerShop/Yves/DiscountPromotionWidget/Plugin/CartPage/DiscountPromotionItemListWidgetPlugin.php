<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Plugin\CartPage;

use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\DiscountPromotionWidget\DiscountPromotionItemListWidgetPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\DiscountPromotionWidget\DiscountPromotionWidgetFactory getFactory()
 */
class DiscountPromotionItemListWidgetPlugin extends AbstractWidgetPlugin implements DiscountPromotionItemListWidgetPluginInterface
{
    const PARAM_VARIANT_ATTRIBUTES = 'attributes';

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
            ->addParameter('promotionProducts', $this->getPromotionProducts($quoteTransfer, $request));
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
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getPromotionProducts(QuoteTransfer $quoteTransfer, Request $request): array
    {
        $promotionProducts = [];
        foreach ($quoteTransfer->getPromotionItems() as $promotionItemTransfer) {
            $promotionItemTransfer->requireAbstractSku();

            $productStorageData = $this->getFactory()
                ->getProductStorageClient()
                ->getProductAbstractStorageData($promotionItemTransfer->getIdProductAbstract(), $this->getLocale());

            if (!$productStorageData) {
                continue;
            }

            $productViewTransfer = $this->getFactory()
                ->getProductStorageClient()
                ->mapProductStorageData(
                    $productStorageData,
                    $this->getLocale(),
                    $this->getSelectedAttributes($request, $promotionItemTransfer->getAbstractSku())
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
    protected function getSelectedAttributes(Request $request, $abstractSku)
    {
        $selectedAttributes = $request->query->get(static::PARAM_VARIANT_ATTRIBUTES, []);

        return isset($selectedAttributes[$abstractSku]) ? array_filter($selectedAttributes[$abstractSku]) : [];
    }

    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     *
     * @return string
     */
    protected function createPromotionProductBucketIdentifier(PromotionItemTransfer $promotionItemTransfer)
    {
        return $promotionItemTransfer->getAbstractSku() . '-' . $promotionItemTransfer->getIdDiscountPromotion();
    }
}
