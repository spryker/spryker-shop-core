<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Operation;

use Generated\Shared\Transfer\CommentThreadTransfer;

interface CommentOperationInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return void
     */
    public function executeCommentThreadAfterOperationPlugins(CommentThreadTransfer $commentThreadTransfer): void;
}
