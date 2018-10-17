<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\DiscountPromotionWidget\DiscountPromotionItemListWidgetPluginInterface;
use SprykerShop\Yves\DiscountPromotionWidget\Widget\CartDiscountPromotionProductListWidget;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use \SprykerShop\Yves\DiscountPromotionWidget\Widget\CartDiscountPromotionProductListWidget instead.
 */
class DiscountPromotionItemListWidgetPlugin extends AbstractWidgetPlugin implements DiscountPromotionItemListWidgetPluginInterface
{
    public const PARAM_VARIANT_ATTRIBUTES = 'attributes';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, Request $request): void
    {
        $widget = new CartDiscountPromotionProductListWidget($quoteTransfer, $request);

        $this->parameters = $widget->getParameters();
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
        return CartDiscountPromotionProductListWidget::getTemplate();
    }
}
