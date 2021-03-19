<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSalesReturnWidget\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\MerchantSalesReturnWidget\Widget\MerchantSalesReturnCreateFormWidget;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface;

class MerchantSalesReturnCreateFormWidgetCacheKeyGeneratorStrategyPlugin extends AbstractPlugin implements WidgetCacheKeyGeneratorStrategyPluginInterface
{
    /**
     * @param array $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(array $arguments = []): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getWidgetClassName(): string
    {
        return MerchantSalesReturnCreateFormWidget::class;
    }
}
