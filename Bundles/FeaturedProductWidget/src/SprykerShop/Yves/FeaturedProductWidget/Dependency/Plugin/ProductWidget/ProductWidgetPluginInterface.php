<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\FeaturedProductWidget\Dependency\Plugin\ProductWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ProductWidgetPluginInterface extends WidgetPluginInterface
{

    public const NAME = 'ProductWidgetPlugin';

    /**
     * @param array $product
     *
     * @return void
     */
    public function initialize(array $product): void;

}
