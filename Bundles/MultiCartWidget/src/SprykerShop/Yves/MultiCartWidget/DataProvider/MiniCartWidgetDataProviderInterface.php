<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;

interface MiniCartWidgetDataProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getActiveCart(): QuoteTransfer;

    /**
     * @return array<\Generated\Shared\Transfer\QuoteTransfer>
     */
    public function getInActiveQuoteList(): array;

    /**
     * @return bool
     */
    public function isMultiCartAllowed(): bool;
}
