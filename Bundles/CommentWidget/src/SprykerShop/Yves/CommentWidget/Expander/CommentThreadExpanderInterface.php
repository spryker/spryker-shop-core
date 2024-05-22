<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Expander;

use Generated\Shared\Transfer\CommentThreadTransfer;

interface CommentThreadExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function expandCommentsWithPlainTags(CommentThreadTransfer $commentThreadTransfer): CommentThreadTransfer;
}
