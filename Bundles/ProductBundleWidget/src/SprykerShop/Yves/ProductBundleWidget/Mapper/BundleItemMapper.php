<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Mapper;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Symfony\Component\HttpFoundation\Request;

class BundleItemMapper implements BundleItemMapperInterface
{
    /**
     * @uses \SprykerShop\Yves\CartReorderPage\Widget\CartReorderItemCheckboxWidget::PARAMETER_ATTRIBUTE_NAME
     *
     * @var string
     */
    protected const PARAMETER_ATTRIBUTE_NAME = 'attributeName';

    /**
     * @uses \SprykerShop\Yves\CartReorderPage\Widget\CartReorderItemCheckboxWidget::PARAMETER_ATTRIBUTE_VALUE
     *
     * @var string
     */
    protected const PARAMETER_ATTRIBUTE_VALUE = 'attributeValue';

    /**
     * @var string
     */
    protected const ATTRIBUTE_NAME_BUNDLE_ITEM_IDENTIFIERS = 'bundle-item-identifiers';

    /**
     * @param array<string, mixed> $attributes
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array<string, mixed>
     */
    public function mapProductBundleAttributes(array $attributes, ItemTransfer $itemTransfer): array
    {
        if ($itemTransfer->getBundleItemIdentifier()) {
            $attributes[static::PARAMETER_ATTRIBUTE_NAME] = static::ATTRIBUTE_NAME_BUNDLE_ITEM_IDENTIFIERS;
            $attributes[static::PARAMETER_ATTRIBUTE_VALUE] = $itemTransfer->getBundleItemIdentifierOrFail();
        }

        return $attributes;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function mapRequestToCartReorderRequest(
        Request $request,
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): CartReorderRequestTransfer {
        $bundleItemIdentifiers = $request->request->all()[static::ATTRIBUTE_NAME_BUNDLE_ITEM_IDENTIFIERS] ?? null;

        if ($bundleItemIdentifiers) {
            $cartReorderRequestTransfer->setBundleItemIdentifiers($bundleItemIdentifiers);
        }

        return $cartReorderRequestTransfer;
    }
}
