<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerShop\Yves\MerchantWidget\Plugin\ShopApplication;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\MerchantWidget\Widget\SoldByMerchantWidget;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface;

class SoldByMerchantWidgetCacheKeyGeneratorStrategyPlugin extends AbstractPlugin implements WidgetCacheKeyGeneratorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Generates cache for `SoldByMerchantWidget`.
     *
     * @api
     *
     * @param array<string, mixed> $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(array $arguments = []): ?string
    {
        foreach ($arguments as $argument) {
            if ($argument instanceof ItemTransfer && $argument->getMerchantReference()) {
                return md5((string)$argument->getMerchantReference());
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getWidgetClassName(): string
    {
        return SoldByMerchantWidget::class;
    }
}
