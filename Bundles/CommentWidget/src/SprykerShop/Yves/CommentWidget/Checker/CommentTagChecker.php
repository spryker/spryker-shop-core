<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Checker;

use Generated\Shared\Transfer\CommentTagRequestTransfer;
use SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCommentClientInterface;

class CommentTagChecker implements CommentTagCheckerInterface
{
    /**
     * @var \SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCommentClientInterface
     */
    protected $commentClient;

    /**
     * @param \SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCommentClientInterface $commentClient
     */
    public function __construct(CommentWidgetToCommentClientInterface $commentClient)
    {
        $this->commentClient = $commentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return bool
     */
    public function isCommentTagAvailable(CommentTagRequestTransfer $commentTagRequestTransfer): bool
    {
        return in_array($commentTagRequestTransfer->getName(), $this->commentClient->getCommentAvailableTags(), true);
    }
}
