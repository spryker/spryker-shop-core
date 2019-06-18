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
    /**
     * @param \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(CommentThreadResponseTransfer $commentThreadResponseTransfer): void
    {
        foreach ($commentThreadResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
