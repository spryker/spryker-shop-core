<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Widget;

use Generated\Shared\Transfer\CommentThreadTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentWidget extends AbstractWidget
{
    protected const PARAMETER_OWNER_ID = 'ownerId';
    protected const PARAMETER_OWNER_TYPE = 'ownerType';
    protected const PARAMETER_COMMENT_THREAD = 'commentThread';

    /**
     * @param int $ownerId
     * @param string $ownerType
     * @param \Generated\Shared\Transfer\CommentThreadTransfer|null $commentThreadTransfer
     */
    public function __construct(
        int $ownerId,
        string $ownerType,
        ?CommentThreadTransfer $commentThreadTransfer
    ) {
        $this->addOwnerIdParameter($ownerId);
        $this->addOwnerTypeParameter($ownerType);
        $this->addCommentThreadParameter($commentThreadTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CommentCartWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CommentWidget/views/comment/comment.twig';
    }

    /**
     * @param int $ownerId
     *
     * @return void
     */
    protected function addOwnerIdParameter(int $ownerId): void
    {
        $this->addParameter(static::PARAMETER_OWNER_ID, $ownerId);
    }

    /**
     * @param string $ownerType
     *
     * @return void
     */
    protected function addOwnerTypeParameter(string $ownerType): void
    {
        $this->addParameter(static::PARAMETER_OWNER_TYPE, $ownerType);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer|null $commentThreadTransfer
     *
     * @return void
     */
    protected function addCommentThreadParameter(?CommentThreadTransfer $commentThreadTransfer): void
    {
        $this->addParameter(static::PARAMETER_COMMENT_THREAD, $commentThreadTransfer);
    }
}
