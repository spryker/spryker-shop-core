<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CommentWidgetConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Shared\Comment\CommentConfig::COMMENT_AVAILABLE_TAGS
     */
    protected const COMMENT_AVAILABLE_TAGS = [];

    /**
     * @return string[]
     */
    public function getCommentAvailableTags(): array
    {
        return static::COMMENT_AVAILABLE_TAGS;
    }
}
