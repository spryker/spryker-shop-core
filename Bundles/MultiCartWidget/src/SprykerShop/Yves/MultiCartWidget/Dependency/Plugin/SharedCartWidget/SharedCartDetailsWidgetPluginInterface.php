<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;

interface SharedCartDetailsWidgetPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'SharedCartDetailsWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, mixed> $actions
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, array $actions): void;
}
