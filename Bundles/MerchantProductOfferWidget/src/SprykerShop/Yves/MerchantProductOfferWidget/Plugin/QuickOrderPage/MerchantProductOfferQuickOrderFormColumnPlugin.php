<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOfferQuickOrderFormColumnPlugin extends AbstractPlugin implements QuickOrderFormColumnPluginInterface
{
    /**
     * @var string
     */
    protected const COLUMN_TITLE = 'quick-order.input-label.merchant';

    /**
     * @var string
     */
    protected const DATA_PATH = 'product_offer_reference';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getColumnTitle(): string
    {
        return static::COLUMN_TITLE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDataPath(): string
    {
        return static::DATA_PATH;
    }
}
