<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class WishlistPageRouteProviderPlugin extends AbstractRouteProviderPlugin
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
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addAddToWishlistRoute($routeCollection);
        $routeCollection = $this->addWishlistOverviewRoute($routeCollection);
        $routeCollection = $this->addUpdateWishlistRoute($routeCollection);
        $routeCollection = $this->addDeleteWishlistRoute($routeCollection);
        $routeCollection = $this->addWishlistDetailsRoute($routeCollection);
        $routeCollection = $this->addAddItemRoute($routeCollection);
        $routeCollection = $this->addRemoveItemRoute($routeCollection);
        $routeCollection = $this->addMoveToCartRoute($routeCollection);
        $routeCollection = $this->addMoveAllAvailableToCartRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addAddToWishlistRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/add-to-wishlist', 'WishlistPage', 'AddToWishlist', 'indexAction');
        $routeCollection->add(static::ROUTE_ADD_TO_WISHLIST, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addWishlistOverviewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist', 'WishlistPage', 'WishlistOverview', 'indexAction');
        $routeCollection->add(static::ROUTE_WISHLIST_OVERVIEW, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addUpdateWishlistRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/update/{wishlistName}', 'WishlistPage', 'WishlistOverview', 'updateAction');
        $route = $route->setRequirement('wishlistName', '.+');
        $routeCollection->add(static::ROUTE_WISHLIST_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addDeleteWishlistRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/delete/{wishlistName}', 'WishlistPage', 'WishlistOverview', 'deleteAction');
        $route = $route->setRequirement('wishlistName', '.+');
        $routeCollection->add(static::ROUTE_WISHLIST_DELETE, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addWishlistDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/details/{wishlistName}', 'WishlistPage', 'Wishlist', 'indexAction');
        $route = $route->setRequirement('wishlistName', '.+');
        $routeCollection->add(static::ROUTE_WISHLIST_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addAddItemRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/add-item', 'WishlistPage', 'Wishlist', 'addItemAction');
        $routeCollection->add(static::ROUTE_ADD_ITEM, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveItemRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/remove-item', 'WishlistPage', 'Wishlist', 'removeItemAction');
        $routeCollection->add(static::ROUTE_REMOVE_ITEM, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addMoveToCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/move-to-cart', 'WishlistPage', 'Wishlist', 'moveToCartAction');
        $route = $route->setRequirement('sku', '[a-zA-Z0-9-_]+');
        $routeCollection->add(static::ROUTE_MOVE_TO_CART, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addMoveAllAvailableToCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/move-all-available-to-cart/{wishlistName}', 'WishlistPage', 'Wishlist', 'moveAllAvailableToCartAction');
        $route = $route->setRequirement('wishlistName', '.+');
        $routeCollection->add(static::ROUTE_MOVE_ALL_AVAILABLE_TO_CART, $route);

        return $routeCollection;
    }
}
