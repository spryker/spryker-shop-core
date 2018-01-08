<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class WishlistPageControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_ADD_TO_WISHLIST = 'wishlist/add-to-wishlist';
    const ROUTE_WISHLIST_OVERVIEW = 'wishlist/overview';
    const ROUTE_WISHLIST_UPDATE = 'wishlist/update';
    const ROUTE_WISHLIST_DELETE = 'wishlist/delete';
    const ROUTE_WISHLIST_DETAILS = 'wishlist/details';
    const ROUTE_ADD_ITEM = 'wishlist/add-item';
    const ROUTE_REMOVE_ITEM = 'wishlist/remove-item';
    const ROUTE_MOVE_TO_CART = 'wishlist/move-to-cart';
    const ROUTE_MOVE_ALL_AVAILABLE_TO_CART = 'wishlist/move-all-available-to-cart';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createGetController('/{wishlist}/add-to-wishlist', static::ROUTE_ADD_TO_WISHLIST, 'WishlistPage', 'AddToWishlist')
            ->assert('wishlist', $allowedLocalesPattern . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        $this->createController('/{wishlist}', static::ROUTE_WISHLIST_OVERVIEW, 'WishlistPage', 'WishlistOverview')
            ->assert('wishlist', $allowedLocalesPattern . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        $this->createController('/{wishlist}/update/{wishlistName}', static::ROUTE_WISHLIST_UPDATE, 'WishlistPage', 'WishlistOverview', 'update')
            ->assert('wishlist', $allowedLocalesPattern . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', '.+');

        $this->createPostController('/{wishlist}/delete/{wishlistName}', static::ROUTE_WISHLIST_DELETE, 'WishlistPage', 'WishlistOverview', 'delete')
            ->assert('wishlist', $allowedLocalesPattern . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', '.+');

        $this->createGetController('/{wishlist}/details/{wishlistName}', static::ROUTE_WISHLIST_DETAILS, 'WishlistPage', 'Wishlist')
            ->assert('wishlist', $allowedLocalesPattern . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', '.+');

        $this->createPostController('/{wishlist}/add-item', static::ROUTE_ADD_ITEM, 'WishlistPage', 'Wishlist', 'addItem')
            ->assert('wishlist', $allowedLocalesPattern . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        $this->createPostController('/{wishlist}/remove-item', static::ROUTE_REMOVE_ITEM, 'WishlistPage', 'Wishlist', 'removeItem')
            ->assert('wishlist', $allowedLocalesPattern . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        $this->createPostController('/{wishlist}/move-to-cart', static::ROUTE_MOVE_TO_CART, 'WishlistPage', 'Wishlist', 'moveToCart')
            ->assert('wishlist', $allowedLocalesPattern . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('sku', '[a-zA-Z0-9-_]+');

        $this->createPostController('/{wishlist}/move-all-available-to-cart/{wishlistName}', static::ROUTE_MOVE_ALL_AVAILABLE_TO_CART, 'WishlistPage', 'Wishlist', 'moveAllAvailableToCart')
            ->assert('wishlist', $allowedLocalesPattern . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', '.+');
    }
}
