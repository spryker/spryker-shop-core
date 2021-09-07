<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartAddProductAsSeparateItemWidget extends AbstractWidget
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_SESSION
     * @var string
     */
    protected const STORAGE_STRATEGY_SESSION = 'session';

    /**
     * @var string
     */
    protected const PARAMETER_IS_QUOTE_STORAGE_STRATEGY_SESSION_ENABLED = 'isQuoteStorageStrategySessionEnabled';

    public function __construct()
    {
        $this->addIsQuoteStorageStrategySessionEnabledParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartAddProductAsSeparateItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartPage/views/cart-add-product-as-separate-item/cart-add-product-as-separate-item.twig';
    }

    /**
     * @return void
     */
    protected function addIsQuoteStorageStrategySessionEnabledParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_QUOTE_STORAGE_STRATEGY_SESSION_ENABLED, $this->isQuoteStorageStrategySessionEnabled());
    }

    /**
     * @return bool
     */
    protected function isQuoteStorageStrategySessionEnabled(): bool
    {
        $storageStrategy = $this->getFactory()
            ->getQuoteClient()
            ->getStorageStrategy();

        return $storageStrategy === static::STORAGE_STRATEGY_SESSION;
    }
}
