<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-05-07
 * Time: 10:00
 */

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductViewTransferExpanderPluginInterface
{
    /**
     * Specification:
     * - expand and returns ProductViewTransfer. Used in ShoppingListItemWidget::expandProductViewTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expand(ProductViewTransfer $productViewTransfer): ProductViewTransfer;
}
