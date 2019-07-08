<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Checker;

use Generated\Shared\Transfer\CommentTransfer;

class CommentChecker implements CommentCheckerInterface
{
    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::COMMENT_MESSAGE_MIN_LENGTH
     */
    protected const COMMENT_MESSAGE_MIN_LENGTH = 1;

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::COMMENT_MESSAGE_MAX_LENGTH
     */
    protected const COMMENT_MESSAGE_MAX_LENGTH = 5000;

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return bool
     */
    public function checkCommentMessageLength(CommentTransfer $commentTransfer): bool
    {
        $messageLength = mb_strlen($commentTransfer->getMessage());

        return $messageLength >= static::COMMENT_MESSAGE_MIN_LENGTH && $messageLength <= static::COMMENT_MESSAGE_MAX_LENGTH;
    }
}
