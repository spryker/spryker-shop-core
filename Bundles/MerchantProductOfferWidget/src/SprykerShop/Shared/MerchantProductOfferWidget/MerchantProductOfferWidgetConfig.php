<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\MerchantProductOfferWidget;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class MerchantProductOfferWidgetConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Dimension type as used for product offer price.
     *
     * @api
     *
     * @see \Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig::DIMENSION_TYPE.
     */
    public const DIMENSION_TYPE = 'OFFER';
}
