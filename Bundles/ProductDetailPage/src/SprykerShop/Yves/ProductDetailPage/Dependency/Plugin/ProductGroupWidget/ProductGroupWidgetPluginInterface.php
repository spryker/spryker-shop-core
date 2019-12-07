<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductGroupWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductGroupWidget\Widget\ProductGroupColorWidget instead.
 */
interface ProductGroupWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductGroupWidgetPlugin';

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function initialize(int $idProductAbstract): void;
}
