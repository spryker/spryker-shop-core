<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointCartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class ServicePointCartPageConfig extends AbstractBundleConfig
{
    /**
     * @var list<string>
     */
    protected const QUOTE_ITEM_FIELDS_ALLOWED_FOR_RESET = [
        ItemTransfer::SERVICE_POINT,
    ];

    /**
     * Specification:
     * - Returns the list of required unset properties for failed replacement items.
     *
     * @api
     *
     * @return list<string>
     */
    public function getQuoteItemFieldsAllowedForReset(): array
    {
        return static::QUOTE_ITEM_FIELDS_ALLOWED_FOR_RESET;
    }
}
