<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Widget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ConfigurableBundleWidget\ConfigurableBundleWidgetConfig getConfig()
 */
class OrderConfiguredBundleCartItemWidget extends AbstractWidget
{
    protected const PARAMETER_ORDER = 'order';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function __construct(OrderTransfer $orderTransfer)
    {
        $this->addOrderParameter($orderTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addOrderParameter(OrderTransfer $orderTransfer): void
    {
        $this->addParameter(static::PARAMETER_ORDER, $orderTransfer);
    }
}
