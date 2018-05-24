<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\WishlistWidget\WishlistMenuItemWidgetPluginInterface;

/**
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
        $wishistCollection = [];
        $isActivePage = false;
        $activeWishlistId = null;
        if ($activePage === static::PAGE_KEY_WISHLIST) {
            $isActivePage = true;
            $activeWishlistId = $activeEntityId;
            $wishistCollection = $this->getCustomerWishlistCollection();
        }

        $this->addParameter('wishlistCollection', $wishistCollection)
            ->addParameter('isActivePage', $isActivePage)
            ->addParameter('activeWishlistId', $activeWishlistId);
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer[]
     */
    protected function getCustomerWishlistCollection(): array
    {
        return (array)$this->getFactory()
            ->getWishlistClient()
            ->getCustomerWishlistCollection()
            ->getWishlists();
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
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
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@WishlistWidget/views/wishlist-menu-item/wishlist-menu-item.twig';
    }
}
