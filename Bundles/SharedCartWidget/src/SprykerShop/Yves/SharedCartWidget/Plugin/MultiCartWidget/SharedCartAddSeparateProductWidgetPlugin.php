<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget\SharedCartAddSeparateProductWidgetPluginInterface;

/**
 * @deprecated Use molecule('shared-cart-add-product-as-separate-item', 'SharedCartWidget') instead.
 */
class SharedCartAddSeparateProductWidgetPlugin extends AbstractWidgetPlugin implements SharedCartAddSeparateProductWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SharedCartWidget/views/shared-cart-separate-product/shared-cart-separate-product.twig';
    }
}
