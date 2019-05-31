<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CommentWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_COMMENT_ADD_COMMENT = 'comment/add-comment';
    protected const ROUTE_COMMENT_UPDATE_COMMENT = 'comment/update-comment';
    protected const ROUTE_COMMENT_REMOVE_COMMENT = 'comment/remove-comment';
    protected const ROUTE_COMMENT_UPDATE_COMMENT_TAGS = 'comment/update-comment-tags';

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
            ->addUpdateCommentTagsRoute();
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::addCommentAction()
     *
     * @return $this
     */
    protected function addAddCommentRoute()
    {
        $this->createController('/{comment}/add-comment', static::ROUTE_COMMENT_ADD_COMMENT, 'CommentWidget', 'Comment', 'addComment')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::updateCommentAction()
     *
     * @return $this
     */
    protected function addUpdateCommentRoute()
    {
        $this->createController('/{comment}/update-comment', static::ROUTE_COMMENT_UPDATE_COMMENT, 'CommentWidget', 'Comment', 'updateComment')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::removeCommentAction()
     *
     * @return $this
     */
    protected function addRemoveCommentRoute()
    {
        $this->createController('/{comment}/remove-comment', static::ROUTE_COMMENT_REMOVE_COMMENT, 'CommentWidget', 'Comment', 'removeComment')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::updateCommentTagsAction()
     *
     * @return $this
     */
    protected function addUpdateCommentTagsRoute()
    {
        $this->createController('/{comment}/remove-comment', static::ROUTE_COMMENT_UPDATE_COMMENT_TAGS, 'CommentWidget', 'Comment', 'updateCommentTags')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment');

        return $this;
    }
}
