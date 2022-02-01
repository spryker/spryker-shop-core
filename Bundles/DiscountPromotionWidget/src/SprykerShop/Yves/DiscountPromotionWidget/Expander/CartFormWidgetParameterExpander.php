<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;

class CartFormWidgetParameterExpander implements CartFormWidgetParameterExpanderInterface
{
    /**
     * @uses \SprykerShop\Yves\CartPage\Widget\AddToCartFormWidget::PARAMETER_FORM_NAME_POSTFIX
     *
     * @var string
     */
    protected const PARAMETER_FORM_NAME_POSTFIX = 'formNamePostfix';

    /**
     * @param array<string, mixed> $formParameters
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $formParameters, ProductViewTransfer $productViewTransfer): array
    {
        if (!$productViewTransfer->getPromotionItem()) {
            return $formParameters;
        }

        $formParameters[static::PARAMETER_FORM_NAME_POSTFIX] = (string)$productViewTransfer->getPromotionItemOrFail()->getIdDiscountPromotion();

        return $formParameters;
    }
}
