<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;

interface SharedCartDetailsWidgetPluginInterface
{
    public const NAME = 'SharedCartDetailsWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $actions
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, array $actions): void;
}
