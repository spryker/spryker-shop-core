<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides an interface for expander plugins for the reorder request.
 */
interface CartReorderRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the reorder request transfer with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function expand(CartReorderRequestTransfer $cartReorderRequestTransfer, Request $request): CartReorderRequestTransfer;
}
