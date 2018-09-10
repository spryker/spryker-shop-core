<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\ProductBundleWidget;

use Generated\Shared\Transfer\QuoteTransfer;

interface ProductBundleCartItemsWidgetPluginInterface
{
    public const NAME = 'ProductBundleCartItemsWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int|null $itemDisplayLimit
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, ?int $itemDisplayLimit = null): void;
}
