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
    protected const ROUTE_COMMENT_ADD = 'comment/add';
    protected const ROUTE_COMMENT_UPDATE = 'comment/update';
    protected const ROUTE_COMMENT_REMOVE = 'comment/remove';
    protected const ROUTE_COMMENT_TAG_ATTACH = 'comment/tag/attach';
    protected const ROUTE_COMMENT_TAG_UNATTACH = 'comment/tag/unattach';

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
            ->addAttachCommentRoute()
            ->addUnattachCommentRoute();
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
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentTagController::attachAction()
     *
     * @return $this
     */
    protected function addAttachCommentRoute()
    {
        $this->createController('/{comment}/tag/attach', static::ROUTE_COMMENT_TAG_ATTACH, 'CommentWidget', 'CommentTag', 'attach')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentTagController::unattachAction()
     *
     * @return $this
     */
    protected function addUnattachCommentRoute()
    {
        $this->createController('/{comment}/tag/unattach', static::ROUTE_COMMENT_TAG_UNATTACH, 'CommentWidget', 'CommentTag', 'unattach')
            ->assert('comment', $this->getAllowedLocalesPattern() . 'comment|comment')
            ->value('comment', 'comment');

        return $this;
    }
}
