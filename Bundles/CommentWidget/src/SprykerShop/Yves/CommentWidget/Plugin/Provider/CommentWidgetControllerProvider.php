<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CommentWidget\Plugin\Router\CommentWidgetRouteProviderPlugin` instead.
 */
class CommentWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_COMMENT_ADD = 'comment/add';
    protected const ROUTE_COMMENT_UPDATE = 'comment/update';
    protected const ROUTE_COMMENT_REMOVE = 'comment/remove';
    protected const ROUTE_COMMENT_TAG_ADD = 'comment/tag/add';
    protected const ROUTE_COMMENT_TAG_REMOVE = 'comment/tag/remove';

    protected const UUID_PATTERN = '[a-zA-Z0-9-_\.]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addAddCommentRoute()
            ->addUpdateCommentRoute()
            ->addRemoveCommentRoute()
            ->addAddCommentTagRoute()
            ->addRemoveCommentTagRoute();
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::addAction()
     *
     * @return $this
     */
    protected function addAddCommentRoute()
    {
        $this->createController('/{comment}/add', static::ROUTE_COMMENT_ADD, 'CommentWidget', 'Comment', 'add')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::updateAction()
     *
     * @return $this
     */
    protected function addUpdateCommentRoute()
    {
        $this->createController('/{comment}/update', static::ROUTE_COMMENT_UPDATE, 'CommentWidget', 'Comment', 'update')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::removeAction()
     *
     * @return $this
     */
    protected function addRemoveCommentRoute()
    {
        $this->createController('/{comment}/remove', static::ROUTE_COMMENT_REMOVE, 'CommentWidget', 'Comment', 'remove')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentTagController::addAction()
     *
     * @return $this
     */
    protected function addAddCommentTagRoute()
    {
        $this->createController('/{comment}/{uuid}/tag/add', static::ROUTE_COMMENT_TAG_ADD, 'CommentWidget', 'CommentTag', 'add')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment')
            ->assert('uuid', static::UUID_PATTERN);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentTagController::removeAction()
     *
     * @return $this
     */
    protected function addRemoveCommentTagRoute()
    {
        $this->createController('/{comment}/{uuid}/tag/remove', static::ROUTE_COMMENT_TAG_REMOVE, 'CommentWidget', 'CommentTag', 'remove')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment')
            ->assert('uuid', static::UUID_PATTERN);

        return $this;
    }
}
