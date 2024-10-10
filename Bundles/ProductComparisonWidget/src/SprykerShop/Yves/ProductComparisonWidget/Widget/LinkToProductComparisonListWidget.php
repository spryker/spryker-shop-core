<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductComparisonWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductComparisonWidget\ProductComparisonWidgetFactory getFactory()
 */
class LinkToProductComparisonListWidget extends AbstractWidget
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'LinkToProductComparisonListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductComparisonWidget/views/product-comparison-list-link/product-comparison-list-link.twig';
    }
}
