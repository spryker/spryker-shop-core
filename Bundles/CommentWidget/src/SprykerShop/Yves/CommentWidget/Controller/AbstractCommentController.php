<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentThreadTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class AbstractCommentController extends AbstractController
{
    protected const PARAMETER_UUID = 'uuid';
    protected const PARAMETER_RETURN_ROUTE = 'returnRoute';

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return void
     */
    protected function executeCommentThreadAfterOperationPlugins(CommentThreadTransfer $commentThreadTransfer): void
    {
        $commentThreadAfterOperationPlugins = $this->getFactory()->getCommentThreadAfterOperationPlugins();

        foreach ($commentThreadAfterOperationPlugins as $commentThreadAfterOperationPlugin) {
            if ($commentThreadAfterOperationPlugin->isApplicable($commentThreadTransfer)) {
                $commentThreadAfterOperationPlugin->execute($commentThreadTransfer);
            }
        }
    }
}
