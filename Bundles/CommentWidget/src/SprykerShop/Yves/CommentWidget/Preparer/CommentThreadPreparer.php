<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Preparer;

use Generated\Shared\Transfer\CommentThreadTransfer;

class CommentThreadPreparer implements CommentThreadPreparerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return array<string, array<\Generated\Shared\Transfer\CommentTransfer>>
     */
    public function prepareTaggedComments(CommentThreadTransfer $commentThreadTransfer): array
    {
        $taggedComments = [];

        foreach ($commentThreadTransfer->getComments() as $comment) {
            $taggedComments['all'][] = $comment;

            /** @var array<string> $tagNames */
            $tagNames = $comment->getTagNames();
            foreach ($tagNames as $tagName) {
                $taggedComments[$tagName][] = $comment;
            }
        }

        return $taggedComments;
    }
}
