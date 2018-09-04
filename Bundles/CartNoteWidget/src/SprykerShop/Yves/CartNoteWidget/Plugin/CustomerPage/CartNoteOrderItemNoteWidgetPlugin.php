<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartNoteWidget\Widget\DisplayOrderItemNoteWidget;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\CartNoteWidget\CartNoteOrderItemNoteWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\CartNoteWidget\Widget\DisplayOrderItemNoteWidget instead.
 */
class CartNoteOrderItemNoteWidgetPlugin extends AbstractWidgetPlugin implements CartNoteOrderItemNoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void
    {
        $widget = new DisplayOrderItemNoteWidget($itemTransfer);

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
        return DisplayOrderItemNoteWidget::getTemplate();
    }
}
