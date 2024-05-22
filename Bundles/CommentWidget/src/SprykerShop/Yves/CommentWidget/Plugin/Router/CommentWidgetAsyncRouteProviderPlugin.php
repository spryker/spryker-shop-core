<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class CommentWidgetAsyncRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_COMMENT_ASYNC_ADD = 'comment/async/add';

    /**
     * @var string
     */
    public const ROUTE_NAME_COMMENT_ASYNC_UPDATE = 'comment/async/update';

    /**
     * @var string
     */
    public const ROUTE_NAME_COMMENT_ASYNC_REMOVE = 'comment/async/remove';

    /**
     * @var string
     */
    public const ROUTE_NAME_COMMENT_TAG_ASYNC_ADD = 'comment/tag/async/add';

    /**
     * @var string
     */
    public const ROUTE_NAME_COMMENT_TAG_ASYNC_REMOVE = 'comment/tag/async/remove';

    /**
     * @var string
     */
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
        $routeCollection = $this->addCommentAsyncAddRoute($routeCollection);
        $routeCollection = $this->addCommentAsyncUpdateRoute($routeCollection);
        $routeCollection = $this->addCommentAsyncRemoveRoute($routeCollection);
        $routeCollection = $this->addCommentTagAsyncAddRoute($routeCollection);
        $routeCollection = $this->addCommentTagAsyncRemoveRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentAsyncController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCommentAsyncAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/async/add', 'CommentWidget', 'CommentAsync', 'addAction');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_ASYNC_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentAsyncController::updateAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCommentAsyncUpdateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/async/update', 'CommentWidget', 'CommentAsync', 'updateAction');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_ASYNC_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentAsyncController::removeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCommentAsyncRemoveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/async/remove', 'CommentWidget', 'CommentAsync', 'removeAction');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_ASYNC_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentTagAsyncController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCommentTagAsyncAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/{uuid}/tag/async/add', 'CommentWidget', 'CommentTagAsync', 'addAction');
        $route = $route->setRequirement('uuid', static::UUID_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_TAG_ASYNC_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentTagAsyncController::removeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCommentTagAsyncRemoveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/{uuid}/tag/async/remove', 'CommentWidget', 'CommentTagAsync', 'removeAction');
        $route = $route->setRequirement('uuid', static::UUID_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_TAG_ASYNC_REMOVE, $route);

        return $routeCollection;
    }
}
