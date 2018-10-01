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
    public const ROUTE_ADD_TO_WISHLIST = 'wishlist/add-to-wishlist';
    public const ROUTE_WISHLIST_OVERVIEW = 'wishlist/overview';
    public const ROUTE_WISHLIST_UPDATE = 'wishlist/update';
    public const ROUTE_WISHLIST_DELETE = 'wishlist/delete';
    public const ROUTE_WISHLIST_DETAILS = 'wishlist/details';
    public const ROUTE_ADD_ITEM = 'wishlist/add-item';
    public const ROUTE_REMOVE_ITEM = 'wishlist/remove-item';
    public const ROUTE_MOVE_TO_CART = 'wishlist/move-to-cart';
    public const ROUTE_MOVE_ALL_AVAILABLE_TO_CART = 'wishlist/move-all-available-to-cart';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addAddToWishlistRoute()
            ->addWishlistOverviewRoute()
            ->addUpdateWishlistRoute()
            ->addDeleteWishlistRoute()
            ->addWishlistDetailsRoute()
            ->addAddItemRoute()
            ->addRemoveItemRoute()
            ->addMoveToCartRoute()
            ->addMoveAllAvailableToCartRoute();
    }

    /**
     * @return $this
     */
    protected function addAddToWishlistRoute(): self
    {
        $this->createGetController('/{wishlist}/add-to-wishlist', static::ROUTE_ADD_TO_WISHLIST, 'WishlistPage', 'AddToWishlist')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addWishlistOverviewRoute(): self
    {
        $this->createController('/{wishlist}', static::ROUTE_WISHLIST_OVERVIEW, 'WishlistPage', 'WishlistOverview')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUpdateWishlistRoute(): self
    {
        $this->createController('/{wishlist}/update/{wishlistName}', static::ROUTE_WISHLIST_UPDATE, 'WishlistPage', 'WishlistOverview', 'update')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', '.+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDeleteWishlistRoute(): self
    {
        $this->createPostController('/{wishlist}/delete/{wishlistName}', static::ROUTE_WISHLIST_DELETE, 'WishlistPage', 'WishlistOverview', 'delete')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', '.+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addWishlistDetailsRoute(): self
    {
        $this->createGetController('/{wishlist}/details/{wishlistName}', static::ROUTE_WISHLIST_DETAILS, 'WishlistPage', 'Wishlist')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', '.+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addAddItemRoute(): self
    {
        $this->createController('/{wishlist}/add-item', static::ROUTE_ADD_ITEM, 'WishlistPage', 'Wishlist', 'addItem')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRemoveItemRoute(): self
    {
        $this->createPostController('/{wishlist}/remove-item', static::ROUTE_REMOVE_ITEM, 'WishlistPage', 'Wishlist', 'removeItem')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addMoveToCartRoute(): self
    {
        $this->createPostController('/{wishlist}/move-to-cart', static::ROUTE_MOVE_TO_CART, 'WishlistPage', 'Wishlist', 'moveToCart')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('sku', '[a-zA-Z0-9-_]+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addMoveAllAvailableToCartRoute(): self
    {
        $this->createPostController('/{wishlist}/move-all-available-to-cart/{wishlistName}', static::ROUTE_MOVE_ALL_AVAILABLE_TO_CART, 'WishlistPage', 'Wishlist', 'moveAllAvailableToCart')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', '.+');

        return $this;
    }
}
