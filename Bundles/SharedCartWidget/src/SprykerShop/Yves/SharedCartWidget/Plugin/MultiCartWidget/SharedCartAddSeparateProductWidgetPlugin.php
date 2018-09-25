<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget\SharedCartAddSeparateProductWidgetPluginInterface;

/**
 * @deprecated Use NEW_MOLECULE instead.
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
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SharedCartWidget/views/shared-cart-separate-product/shared-cart-separate-product.twig';
    }
}
