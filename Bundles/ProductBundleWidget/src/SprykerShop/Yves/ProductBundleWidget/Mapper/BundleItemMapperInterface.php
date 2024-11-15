<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Mapper;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Symfony\Component\HttpFoundation\Request;

interface BundleItemMapperInterface
{
    /**
     * @param array<string, mixed> $attributes
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array<string, mixed>
     */
    public function mapProductBundleAttributes(array $attributes, ItemTransfer $itemTransfer): array;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function mapRequestToCartReorderRequest(
        Request $request,
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): CartReorderRequestTransfer;
}
