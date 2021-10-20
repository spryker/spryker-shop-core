<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class WishlistPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_ADD_TO_WISHLIST} instead.
     * @var string
     */
    protected const ROUTE_ADD_TO_WISHLIST = 'wishlist/add-to-wishlist';

    /**
     * @var string
     */
    public const ROUTE_NAME_ADD_TO_WISHLIST = 'wishlist/add-to-wishlist';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_OVERVIEW} instead.
     * @var string
     */
    protected const ROUTE_WISHLIST_OVERVIEW = 'wishlist/overview';

    /**
     * @var string
     */
    public const ROUTE_NAME_WISHLIST_OVERVIEW = 'wishlist/overview';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_UPDATE} instead.
     * @var string
     */
    protected const ROUTE_WISHLIST_UPDATE = 'wishlist/update';

    /**
     * @var string
     */
    public const ROUTE_NAME_WISHLIST_UPDATE = 'wishlist/update';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DELETE} instead.
     * @var string
     */
    protected const ROUTE_WISHLIST_DELETE = 'wishlist/delete';

    /**
     * @var string
     */
    public const ROUTE_NAME_WISHLIST_DELETE = 'wishlist/delete';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_WISHLIST_DETAILS} instead.
     * @var string
     */
    protected const ROUTE_WISHLIST_DETAILS = 'wishlist/details';

    /**
     * @var string
     */
    public const ROUTE_NAME_WISHLIST_DETAILS = 'wishlist/details';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_ADD_ITEM} instead.
     * @var string
     */
    protected const ROUTE_ADD_ITEM = 'wishlist/add-item';

    /**
     * @var string
     */
    public const ROUTE_NAME_ADD_ITEM = 'wishlist/add-item';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_REMOVE_ITEM} instead.
     * @var string
     */
    protected const ROUTE_REMOVE_ITEM = 'wishlist/remove-item';

    /**
     * @var string
     */
    public const ROUTE_NAME_REMOVE_ITEM = 'wishlist/remove-item';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_MOVE_TO_CART} instead.
     * @var string
     */
    protected const ROUTE_MOVE_TO_CART = 'wishlist/move-to-cart';

    /**
     * @var string
     */
    public const ROUTE_NAME_MOVE_TO_CART = 'wishlist/move-to-cart';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\WishlistPage\Plugin\Router\WishlistPageRouteProviderPlugin::ROUTE_NAME_MOVE_ALL_AVAILABLE_TO_CART} instead.
     * @var string
     */
    protected const ROUTE_MOVE_ALL_AVAILABLE_TO_CART = 'wishlist/move-all-available-to-cart';

    /**
     * @var string
     */
    public const ROUTE_NAME_MOVE_ALL_AVAILABLE_TO_CART = 'wishlist/move-all-available-to-cart';

    /**
     * @var string
     */
    protected const WISHLIST_NAME_PATTERN = '.+';

    /**
     * @var string
     */
    protected const SKU_PATTERN = '[a-zA-Z0-9-_.]+';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
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
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddToWishlistRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/add-to-wishlist', 'WishlistPage', 'AddToWishlist', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_ADD_TO_WISHLIST, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addWishlistOverviewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist', 'WishlistPage', 'WishlistOverview', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_WISHLIST_OVERVIEW, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUpdateWishlistRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/update/{wishlistName}', 'WishlistPage', 'WishlistOverview', 'updateAction');
        $route = $route->setRequirement('wishlistName', static::WISHLIST_NAME_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_WISHLIST_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addDeleteWishlistRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/delete/{wishlistName}', 'WishlistPage', 'WishlistOverview', 'deleteAction');
        $route = $route->setRequirement('wishlistName', static::WISHLIST_NAME_PATTERN);
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_WISHLIST_DELETE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addWishlistDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/details/{wishlistName}', 'WishlistPage', 'Wishlist', 'indexAction');
        $route = $route->setRequirement('wishlistName', static::WISHLIST_NAME_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_WISHLIST_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddItemRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/add-item', 'WishlistPage', 'Wishlist', 'addItemAction');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_ADD_ITEM, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveItemRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/remove-item', 'WishlistPage', 'Wishlist', 'removeItemAction');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_REMOVE_ITEM, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMoveToCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/move-to-cart', 'WishlistPage', 'Wishlist', 'moveToCartAction');
        $route = $route->setRequirement('sku', static::SKU_PATTERN);
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_MOVE_TO_CART, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMoveAllAvailableToCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/wishlist/move-all-available-to-cart/{wishlistName}', 'WishlistPage', 'Wishlist', 'moveAllAvailableToCartAction');
        $route = $route->setRequirement('wishlistName', static::WISHLIST_NAME_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_MOVE_ALL_AVAILABLE_TO_CART, $route);

        return $routeCollection;
    }
}
