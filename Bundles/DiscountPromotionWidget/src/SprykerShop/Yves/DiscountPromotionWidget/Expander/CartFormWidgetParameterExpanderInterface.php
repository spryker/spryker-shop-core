<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountPromotionWidget\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;

interface CartFormWidgetParameterExpanderInterface
{
    /**
     * @param array<string, mixed> $formParameters
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $formParameters, ProductViewTransfer $productViewTransfer): array;
}
