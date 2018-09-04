<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\CustomerReorderWidget\CustomerReorderWidgetPluginInterface;
use SprykerShop\Yves\CustomerReorderWidget\Widget\CustomerReorderWidget;

/**
 * @deprecated Use \SprykerShop\Yves\CustomerReorderWidget\Widget\CustomerReorderWidget instead.
 *
 * @method \SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetFactory getFactory()
 */
class CustomerReorderWidgetPlugin extends AbstractWidgetPlugin implements CustomerReorderWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     *
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer, ?ItemTransfer $itemTransfer = null): void
    {
        $widget = new CustomerReorderWidget($orderTransfer, $itemTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return CustomerReorderWidget::getTemplate();
    }
}
