<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Dependency\Plugin\CartListPermissionGroupWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\SharedCartWidget\Widget\CartListPermissionGroupWidget instead.
 */
interface CartListPermissionGroupWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'CartListPermissionGroupWidgetPlugin';

    /**
     * Specification:
     *  - Represents block 'body' which display access level of customer for cart
     *  - Represents block 'actions' which display share and delete actions for cart depends on access level
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $isQuoteDeletable
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, bool $isQuoteDeletable): void;
}
