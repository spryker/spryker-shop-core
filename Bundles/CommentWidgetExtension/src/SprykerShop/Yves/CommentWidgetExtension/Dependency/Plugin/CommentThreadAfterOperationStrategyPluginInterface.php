<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CommentThreadTransfer;

interface CommentThreadAfterOperationStrategyPluginInterface
{
    /**
     * Specification:
     *  - Checks if this plugin is applicable for provided comment thread.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return bool
     */
    public function isApplicable(CommentThreadTransfer $commentThreadTransfer): bool;

    /**
     * Specification:
     *  - Sync comment thread changes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return void
     */
    public function execute(CommentThreadTransfer $commentThreadTransfer): void;
}
