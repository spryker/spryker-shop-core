<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget\Dependency\Plugin\ProductLabelWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductLabelWidget\Widget\ProductAbstractLabelWidget instead.
 */
interface ProductAbstractLabelWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductAbstractLabelWidgetPlugin';

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function initialize(int $idProductAbstract): void;
}
