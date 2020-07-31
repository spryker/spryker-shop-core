<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CommentWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CommentWidget\Plugin\Router\CommentWidgetRouteProviderPlugin::ROUTE_NAME_COMMENT_ADD} instead.
     */
    protected const ROUTE_COMMENT_ADD = 'comment/add';
    public const ROUTE_NAME_COMMENT_ADD = 'comment/add';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CommentWidget\Plugin\Router\CommentWidgetRouteProviderPlugin::ROUTE_NAME_COMMENT_UPDATE} instead.
     */
    protected const ROUTE_COMMENT_UPDATE = 'comment/update';
    public const ROUTE_NAME_COMMENT_UPDATE = 'comment/update';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CommentWidget\Plugin\Router\CommentWidgetRouteProviderPlugin::ROUTE_NAME_COMMENT_REMOVE} instead.
     */
    protected const ROUTE_COMMENT_REMOVE = 'comment/remove';
    public const ROUTE_NAME_COMMENT_REMOVE = 'comment/remove';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CommentWidget\Plugin\Router\CommentWidgetRouteProviderPlugin::ROUTE_NAME_COMMENT_TAG_ADD} instead.
     */
    protected const ROUTE_COMMENT_TAG_ADD = 'comment/tag/add';
    public const ROUTE_NAME_COMMENT_TAG_ADD = 'comment/tag/add';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CommentWidget\Plugin\Router\CommentWidgetRouteProviderPlugin::ROUTE_NAME_COMMENT_TAG_REMOVE} instead.
     */
    protected const ROUTE_COMMENT_TAG_REMOVE = 'comment/tag/remove';
    public const ROUTE_NAME_COMMENT_TAG_REMOVE = 'comment/tag/remove';

    protected const UUID_PATTERN = '[a-zA-Z0-9-_\.]+';

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
        $routeCollection = $this->addAddCommentRoute($routeCollection);
        $routeCollection = $this->addUpdateCommentRoute($routeCollection);
        $routeCollection = $this->addRemoveCommentRoute($routeCollection);
        $routeCollection = $this->addAddCommentTagRoute($routeCollection);
        $routeCollection = $this->addRemoveCommentTagRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddCommentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/add', 'CommentWidget', 'Comment', 'addAction');
        $route = $route->setMethods(['POST']);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::updateAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUpdateCommentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/update', 'CommentWidget', 'Comment', 'updateAction');
        $routeCollection->add(static::ROUTE_NAME_COMMENT_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::removeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveCommentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/remove', 'CommentWidget', 'Comment', 'removeAction');
        $routeCollection->add(static::ROUTE_NAME_COMMENT_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentTagController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddCommentTagRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/{uuid}/tag/add', 'CommentWidget', 'CommentTag', 'addAction');
        $route = $route->setRequirement('uuid', static::UUID_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_TAG_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentTagController::removeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveCommentTagRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/{uuid}/tag/remove', 'CommentWidget', 'CommentTag', 'removeAction');
        $route = $route->setRequirement('uuid', static::UUID_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_TAG_REMOVE, $route);

        return $routeCollection;
    }
}
