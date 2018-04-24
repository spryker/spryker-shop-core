<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;

interface SharedCartMultiCartAddWidgetPluginInterface
{
    public const NAME = 'SharedCartMultiCartAddWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $activeQuoteTransfer
     * @param array $quoteCollection
     * @param bool $isButtonDisabled
     *
     * @return void
     */
    public function initialize(QuoteTransfer $activeQuoteTransfer, array $quoteCollection, bool $isButtonDisabled): void;
}
