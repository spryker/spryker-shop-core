<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;

interface SharedCartPermissionGroupWidgetPluginInterface
{
    public const NAME = 'SharedCartPermissionGroupWidgetPlugin';

    /**
     * Specification:
     * - Inject data to plugin.
     * - Calculate permission type for giver cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void;
}
