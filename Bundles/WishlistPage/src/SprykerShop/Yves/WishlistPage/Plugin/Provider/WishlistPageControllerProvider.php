<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin` instead.
 */
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
    protected const WISHLIST_NAME_PATTERN = '.+';
    protected const SKU_PATTERN = '[a-zA-Z0-9-_.]+';

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
    protected function addAddToWishlistRoute()
    {
        $this->createGetController('/{wishlist}/add-to-wishlist', static::ROUTE_ADD_TO_WISHLIST, 'WishlistPage', 'AddToWishlist')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addWishlistOverviewRoute()
    {
        $this->createController('/{wishlist}', static::ROUTE_WISHLIST_OVERVIEW, 'WishlistPage', 'WishlistOverview')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUpdateWishlistRoute()
    {
        $this->createController('/{wishlist}/update/{wishlistName}', static::ROUTE_WISHLIST_UPDATE, 'WishlistPage', 'WishlistOverview', 'update')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', static::WISHLIST_NAME_PATTERN);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDeleteWishlistRoute()
    {
        $this->createPostController('/{wishlist}/delete/{wishlistName}', static::ROUTE_WISHLIST_DELETE, 'WishlistPage', 'WishlistOverview', 'delete')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', static::WISHLIST_NAME_PATTERN);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addWishlistDetailsRoute()
    {
        $this->createGetController('/{wishlist}/details/{wishlistName}', static::ROUTE_WISHLIST_DETAILS, 'WishlistPage', 'Wishlist')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', static::WISHLIST_NAME_PATTERN);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addAddItemRoute()
    {
        $this->createController('/{wishlist}/add-item', static::ROUTE_ADD_ITEM, 'WishlistPage', 'Wishlist', 'addItem')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRemoveItemRoute()
    {
        $this->createPostController('/{wishlist}/remove-item', static::ROUTE_REMOVE_ITEM, 'WishlistPage', 'Wishlist', 'removeItem')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addMoveToCartRoute()
    {
        $this->createPostController('/{wishlist}/move-to-cart', static::ROUTE_MOVE_TO_CART, 'WishlistPage', 'Wishlist', 'moveToCart')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('sku', static::SKU_PATTERN);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addMoveAllAvailableToCartRoute()
    {
        $this->createPostController('/{wishlist}/move-all-available-to-cart/{wishlistName}', static::ROUTE_MOVE_ALL_AVAILABLE_TO_CART, 'WishlistPage', 'Wishlist', 'moveAllAvailableToCart')
            ->assert('wishlist', $this->getAllowedLocalesPattern() . 'wishlist|wishlist')
            ->value('wishlist', 'wishlist')
            ->assert('wishlistName', static::WISHLIST_NAME_PATTERN);

        return $this;
    }
}
