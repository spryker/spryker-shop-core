<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget\Dependency\Plugin\ProductLabelWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ProductLabelWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'ProductLabelWidgetPlugin';

    /**
     * @param array $idProductLabels
     *
     * @return void
     */
    public function initialize(array $idProductLabels): void;
}
