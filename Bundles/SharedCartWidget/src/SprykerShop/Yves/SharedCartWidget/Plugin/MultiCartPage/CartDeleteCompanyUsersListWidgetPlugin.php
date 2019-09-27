<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\SharedCartWidget\Widget\CartDeleteCompanyUsersListWidget;

/**
 * @deprecated Use \SprykerShop\Yves\SharedCartWidget\Widget\CartDeleteCompanyUsersListWidget instead.
 *
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class CartDeleteCompanyUsersListWidgetPlugin extends AbstractWidgetPlugin
{
    public const NAME = 'CartDeleteCompanyUsersListWidgetPlugin';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $widget = new CartDeleteCompanyUsersListWidget($quoteTransfer);

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
        return CartDeleteCompanyUsersListWidget::getTemplate();
    }
}
