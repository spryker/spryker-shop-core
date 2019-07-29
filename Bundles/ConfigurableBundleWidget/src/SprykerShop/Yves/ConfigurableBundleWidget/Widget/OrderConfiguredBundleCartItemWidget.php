<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetConfig getConfig()
 */
class OrderConfiguredBundleCartItemWidget extends AbstractWidget
{
    public function __construct()
    {
        // TODO: TBD
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'OrderConfiguredBundleCartItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ConfigurableBundleWidget/views/order-configured-bundle-cart-item-widget/order-configured-bundle-cart-item-widget.twig';
    }
}
