<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget\Dependency\Plugin\ProductLabelWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductLabelWidget\Widget\ProductConcreteLabelWidget instead.
 */
interface ProductLabelWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductLabelWidgetPlugin';

    /**
     * @api
     *
     * @param int[] $idProductLabels
     *
     * @return void
     */
    public function initialize(array $idProductLabels): void;
}
