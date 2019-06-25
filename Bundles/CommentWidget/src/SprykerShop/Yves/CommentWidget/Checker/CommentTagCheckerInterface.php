<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Checker;

use Generated\Shared\Transfer\CommentTagRequestTransfer;

interface CommentTagCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return bool
     */
    public function isCommentTagAvailable(CommentTagRequestTransfer $commentTagRequestTransfer): bool;
}
