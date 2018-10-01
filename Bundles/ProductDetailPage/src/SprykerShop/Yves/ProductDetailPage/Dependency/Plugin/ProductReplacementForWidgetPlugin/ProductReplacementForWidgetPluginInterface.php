<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductReplacementForWidgetPlugin;

interface ProductReplacementForWidgetPluginInterface
{
    public const NAME = 'ProductReplacementForWidgetPlugin';

    /**
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function initialize(string $sku): void;
}
