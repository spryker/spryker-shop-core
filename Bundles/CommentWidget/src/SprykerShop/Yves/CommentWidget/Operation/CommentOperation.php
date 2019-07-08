<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Operation;

use Generated\Shared\Transfer\CommentThreadTransfer;

class CommentOperation implements CommentOperationInterface
{
    /**
     * @var \SprykerShop\Yves\CommentWidgetExtension\Dependency\Plugin\CommentThreadAfterOperationStrategyPluginInterface[]
     */
    protected $commentThreadAfterOperationStrategyPlugins;

    /**
     * @param \SprykerShop\Yves\CommentWidgetExtension\Dependency\Plugin\CommentThreadAfterOperationStrategyPluginInterface[] $commentThreadAfterOperationStrategyPlugins
     */
    public function __construct(array $commentThreadAfterOperationStrategyPlugins)
    {
        $this->commentThreadAfterOperationStrategyPlugins = $commentThreadAfterOperationStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return void
     */
    public function executeCommentThreadAfterOperationPlugins(CommentThreadTransfer $commentThreadTransfer): void
    {
        foreach ($this->commentThreadAfterOperationStrategyPlugins as $commentThreadAfterOperationPlugin) {
            if ($commentThreadAfterOperationPlugin->isApplicable($commentThreadTransfer)) {
                $commentThreadAfterOperationPlugin->execute($commentThreadTransfer);
            }
        }
    }
}
