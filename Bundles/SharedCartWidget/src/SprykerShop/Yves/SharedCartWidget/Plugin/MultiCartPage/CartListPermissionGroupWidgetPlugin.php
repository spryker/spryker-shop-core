<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\SharedCartWidget\Widget\CartListPermissionGroupWidget;

/**
 * @deprecated Use \SprykerShop\Yves\SharedCartWidget\Widget\CartListPermissionGroupWidget instead.
 *
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class CartListPermissionGroupWidgetPlugin extends AbstractWidgetPlugin
{
    public const NAME = 'CartListPermissionGroupWidgetPlugin';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $isQuoteDeletable
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, bool $isQuoteDeletable): void
    {
        $widget = new CartListPermissionGroupWidget($quoteTransfer, $isQuoteDeletable);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return CartListPermissionGroupWidget::getTemplate();
    }
}
