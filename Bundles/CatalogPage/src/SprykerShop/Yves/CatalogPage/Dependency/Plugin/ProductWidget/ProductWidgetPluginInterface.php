<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Plugin\ProductWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ProductWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductWidgetPlugin';

    /**
     * @param array $product
     * @param string|null $viewMode
     *
     * @return void
     */
    public function initialize(array $product, $viewMode = null): void;
}
