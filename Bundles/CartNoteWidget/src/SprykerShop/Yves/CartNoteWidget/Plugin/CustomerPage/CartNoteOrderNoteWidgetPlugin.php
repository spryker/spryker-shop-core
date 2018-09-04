<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartNoteWidget\Widget\DisplayOrderNoteWidget;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\CartNoteWidget\CartNoteOrderNoteWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\CartNoteWidget\Widget\DisplayOrderNoteWidget instead.
 */
class CartNoteOrderNoteWidgetPlugin extends AbstractWidgetPlugin implements CartNoteOrderNoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer): void
    {
        $widget = new DisplayOrderNoteWidget($orderTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return DisplayOrderNoteWidget::getTemplate();
    }
}
