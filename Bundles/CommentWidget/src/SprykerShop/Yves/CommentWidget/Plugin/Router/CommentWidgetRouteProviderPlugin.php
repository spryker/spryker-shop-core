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
    protected const ROUTE_COMMENT_ADD = 'comment/add';
    protected const ROUTE_COMMENT_UPDATE = 'comment/update';
    protected const ROUTE_COMMENT_REMOVE = 'comment/remove';
    protected const ROUTE_COMMENT_TAG_ADD = 'comment/tag/add';
    protected const ROUTE_COMMENT_TAG_REMOVE = 'comment/tag/remove';

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
        $routeCollection->add(static::ROUTE_COMMENT_ADD, $route);

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
        $routeCollection->add(static::ROUTE_COMMENT_UPDATE, $route);

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
        $routeCollection->add(static::ROUTE_COMMENT_REMOVE, $route);

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
        $routeCollection->add(static::ROUTE_COMMENT_TAG_ADD, $route);

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
        $routeCollection->add(static::ROUTE_COMMENT_TAG_REMOVE, $route);

        return $routeCollection;
    }
}
