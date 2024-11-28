<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\StoreWidget\StoreWidgetConstants;

class StoreWidgetConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const STORE_CODE_INDEX = 0;

    /**
     * Specification:
     * - Returns the index of the store code in the URL path.
     * - The index refers to the position of the store code in array resulted by separating URL by `/`.
     * - Example: For `/DE/en/product/123`, if the index is 0, the store code would be 'DE'.
     *
     * @api
     *
     * @return int
     */
    public function getStoreCodeIndex(): int
    {
        return static::STORE_CODE_INDEX;
    }

    /**
     * Specification:
     * - Returns true if the store routing is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isStoreRoutingEnabled(): bool
    {
        return $this->get(StoreWidgetConstants::IS_STORE_ROUTING_ENABLED, false);
    }
}
