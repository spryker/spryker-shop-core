<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\CommentWidget;

use Generated\Shared\Transfer\CommentThreadTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CommentWidgetExtension\Dependency\Plugin\CommentThreadAfterOperationStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartCommentThreadAfterOperationStrategyPlugin extends AbstractPlugin implements CommentThreadAfterOperationStrategyPluginInterface
{
    protected const COMMENT_THREAD_QUOTE_OWNER_TYPE = 'quote';

    /**
     * {@inheritDoc}
     * - Checks if provided owner type related to quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return bool
     */
    public function isApplicable(CommentThreadTransfer $commentThreadTransfer): bool
    {
        return $commentThreadTransfer->getOwnerType() === static::COMMENT_THREAD_QUOTE_OWNER_TYPE;
    }

    /**
     * {@inheritDoc}
     *  - Updates session quote with comment thread.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return void
     */
    public function execute(CommentThreadTransfer $commentThreadTransfer): void
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        $quoteTransfer->setCommentThread($commentThreadTransfer);

        $this->getFactory()
            ->getQuoteClient()
            ->setQuote($quoteTransfer);
    }
}
