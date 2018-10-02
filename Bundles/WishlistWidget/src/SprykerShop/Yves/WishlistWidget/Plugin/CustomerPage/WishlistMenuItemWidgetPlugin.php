<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\WishlistWidget\WishlistMenuItemWidgetPluginInterface;
use SprykerShop\Yves\WishlistWidget\Widget\WishlistMenuItemWidget;

/**
 * @deprecated Use \SprykerShop\Yves\WishlistWidget\Widget\WishlistMenuItemWidget instead.
 *
 * @method \SprykerShop\Yves\WishlistWidget\WishlistWidgetFactory getFactory()
 */
class WishlistMenuItemWidgetPlugin extends AbstractWidgetPlugin implements WishlistMenuItemWidgetPluginInterface
{
    protected const PAGE_KEY_WISHLIST = 'wishlist';

    /**
     * @param string $activePage
     * @param int|null $activeEntityId
     *
     * @return void
     */
    public function initialize(string $activePage, ?int $activeEntityId = null): void
    {
        $widget = new WishlistMenuItemWidget($activePage, $activeEntityId);

        $this->parameters = $widget->getParameters();
    }

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
        return static::NAME;
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
        return WishlistMenuItemWidget::getTemplate();
    }
}
