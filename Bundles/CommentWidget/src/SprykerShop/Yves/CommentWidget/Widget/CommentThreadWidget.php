<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Widget;

use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetConfig getConfig()
 */
class CommentThreadWidget extends AbstractWidget
{
    protected const PARAMETER_RETURN_URL = 'returnUrl';
    protected const PARAMETER_COMMENT_THREAD = 'commentThread';
    protected const PARAMETER_CUSTOMER = 'customer';
    protected const PARAMETER_COMMENT_AVAILABLE_TAGS = 'commentAvailableTags';

    /**
     * @param int $ownerId
     * @param string $ownerType
     * @param string $returnUrl
     * @param \Generated\Shared\Transfer\CommentThreadTransfer|null $commentThreadTransfer
     */
    public function __construct(
        int $ownerId,
        string $ownerType,
        string $returnUrl,
        ?CommentThreadTransfer $commentThreadTransfer
    ) {
        $commentThreadTransfer = $commentThreadTransfer ?: (new CommentThreadTransfer())
            ->setOwnerId($ownerId)
            ->setOwnerType($ownerType);

        $this->expandCommentsWithPlainTags($commentThreadTransfer);

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $this->addReturnUrlParameter($returnUrl);
        $this->addCommentThreadParameter($commentThreadTransfer);
        $this->addCustomerParameter($customerTransfer);
        $this->addCommentAvailableTags();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CommentThreadWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CommentWidget/views/comment-thread/comment-thread.twig';
    }

    /**
     * @param string $returnUrl
     *
     * @return void
     */
    protected function addReturnUrlParameter(string $returnUrl): void
    {
        $this->addParameter(static::PARAMETER_RETURN_URL, $returnUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return void
     */
    protected function addCommentThreadParameter(CommentThreadTransfer $commentThreadTransfer): void
    {
        $this->addParameter(static::PARAMETER_COMMENT_THREAD, $commentThreadTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function addCustomerParameter(CustomerTransfer $customerTransfer): void
    {
        $this->addParameter(static::PARAMETER_CUSTOMER, $customerTransfer);
    }

    /**
     * @return void
     */
    protected function addCommentAvailableTags(): void
    {
        $this->addParameter(static::PARAMETER_COMMENT_AVAILABLE_TAGS, $this->getConfig()->getCommentAvailableTags());
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    protected function expandCommentsWithPlainTags(CommentThreadTransfer $commentThreadTransfer): CommentThreadTransfer
    {
        foreach ($commentThreadTransfer->getComments() as $commentTransfer) {
            $commentTransfer->setTagNames($this->mapCommentTags($commentTransfer));
        }

        return $commentThreadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return array
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
