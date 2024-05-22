<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Expander;

use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;

class CommentThreadExpander implements CommentThreadExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function expandCommentsWithPlainTags(CommentThreadTransfer $commentThreadTransfer): CommentThreadTransfer
    {
        foreach ($commentThreadTransfer->getComments() as $commentTransfer) {
            $commentTransfer->setTagNames($this->mapCommentTags($commentTransfer));
        }

        return $commentThreadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return list<string>
     */
    protected function mapCommentTags(CommentTransfer $commentTransfer): array
    {
        $tags = [];

        foreach ($commentTransfer->getCommentTags() as $commentTagTransfer) {
            $tags[] = $commentTagTransfer->getName();
        }

        return $tags;
    }
}
