<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentWidgetAbstractController extends AbstractController
{
    protected const PARAMETER_RETURN_URL = 'returnUrl';

    /**
     * @param \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer
     *
     * @return void
     */
    protected function handleResponseMessages(CommentThreadResponseTransfer $commentThreadResponseTransfer): void
    {
        if ($commentThreadResponseTransfer->getIsSuccessful()) {
            return;
        }

        foreach ($commentThreadResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer
     *
     * @return void
     */
    protected function executeCommentThreadAfterSuccessfulOperation(CommentThreadResponseTransfer $commentThreadResponseTransfer): void
    {
        if (!$commentThreadResponseTransfer->getIsSuccessful()) {
            return;
        }

        $this->getFactory()
            ->createCommentOperation()
            ->executeCommentThreadAfterOperationPlugins($commentThreadResponseTransfer->getCommentThread());
    }
}
