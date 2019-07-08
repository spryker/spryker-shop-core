<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Checker;

use Generated\Shared\Transfer\CommentTransfer;

interface CommentCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return bool
     */
    public function checkCommentMessageLength(CommentTransfer $commentTransfer): bool;
}
