<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\CartReorderPage;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin\CartReorderRequestExpanderPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleCartReorderRequestExpanderPlugin extends AbstractPlugin implements CartReorderRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `CartReorderRequestTransfer` with bundle item identifiers.
     * - Sets `CartReorderRequestTransfer.bundleItemIdentifiers` with the data provided by the `bundle-item-identifiers` request parameter key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function expand(CartReorderRequestTransfer $cartReorderRequestTransfer, Request $request): CartReorderRequestTransfer
    {
        return $this->getFactory()->createBundleItemMapper()->mapRequestToCartReorderRequest($request, $cartReorderRequestTransfer);
    }
}
