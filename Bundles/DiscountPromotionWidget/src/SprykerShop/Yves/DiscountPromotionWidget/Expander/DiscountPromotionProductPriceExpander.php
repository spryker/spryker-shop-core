<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Expander;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountCalculationRequestTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;
use SprykerShop\Yves\DiscountPromotionWidget\Dependency\Service\DiscountPromotionWidgetToDiscountServiceInterface;

class DiscountPromotionProductPriceExpander implements DiscountPromotionProductPriceExpanderInterface
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
     * @var \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Service\DiscountPromotionWidgetToDiscountServiceInterface
     */
    protected $discountService;

    /**
     * @param \SprykerShop\Yves\DiscountPromotionWidget\Dependency\Service\DiscountPromotionWidgetToDiscountServiceInterface $discountService
     */
    public function __construct(DiscountPromotionWidgetToDiscountServiceInterface $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandWithDiscountPromotionalPrice(
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

        $discountCalculationResponseTransfer = $this->discountService
            ->calculate($discountCalculationRequestTransfer);

        $productViewPrices = [];
        $productViewPrices[static::PRICE_TYPE_ORIGINAL] = $productViewDefaultPrice;

        $productViewPrices[static::PRICE_TYPE_DEFAULT] = $productViewDefaultPrice - $discountCalculationResponseTransfer->getAmount();
        if ($productViewPrices[static::PRICE_TYPE_DEFAULT] < 0) {
            $productViewPrices[static::PRICE_TYPE_DEFAULT] = 0;
        }

        $productViewTransfer->setPrices($productViewPrices);

        return $productViewTransfer;
    }
}
