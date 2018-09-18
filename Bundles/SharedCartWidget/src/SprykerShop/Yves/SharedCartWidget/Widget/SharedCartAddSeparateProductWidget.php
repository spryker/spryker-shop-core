<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SharedCartAddSeparateProductWidget extends AbstractWidget
{
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
        return 'SharedCartAddSeparateProductWidget';
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
