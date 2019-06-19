<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Form\DataProvider;

use Generated\Shared\Transfer\CommentThreadTransfer;

class CommentFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer|null $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function getData(?CommentThreadTransfer $commentThreadTransfer): CommentThreadTransfer
    {
        if (!$commentThreadTransfer) {
            return new CommentThreadTransfer();
        }

        $commentThreadTransfer = $this->expandCommentThread($commentThreadTransfer);

        return $commentThreadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    protected function expandCommentThread(CommentThreadTransfer $commentThreadTransfer): CommentThreadTransfer
    {
        return $commentThreadTransfer;
    }
}
