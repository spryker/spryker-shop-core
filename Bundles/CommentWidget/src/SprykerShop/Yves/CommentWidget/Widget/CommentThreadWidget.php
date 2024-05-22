<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Widget;

use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetConfig getConfig()
 */
class CommentThreadWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_OWNER_ID = 'ownerId';

    /**
     * @var string
     */
    protected const PARAMETER_OWNER_TYPE = 'ownerType';

    /**
     * @var string
     */
    protected const PARAMETER_RETURN_URL = 'returnUrl';

    /**
     * @var string
     */
    protected const PARAMETER_CUSTOMER = 'customer';

    /**
     * @var string
     */
    protected const PARAMETER_TAGGED_COMMENTS = 'taggedComments';

    /**
     * @var string
     */
    protected const PARAMETER_AVAILABLE_COMMENT_TAGS = 'availableCommentTags';

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
        $commentThreadTransfer = $commentThreadTransfer ?: new CommentThreadTransfer();
        $this->getFactory()
            ->createCommentThreadExpander()
            ->expandCommentsWithPlainTags($commentThreadTransfer);

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $taggedComments = $this->getFactory()
            ->createCommentThreadPreparer()
            ->prepareTaggedComments($commentThreadTransfer);

        $this->addOwnerIdParameter($ownerId);
        $this->addOwnerTypeParameter($ownerType);
        $this->addReturnUrlParameter($returnUrl);
        $this->addCustomerParameter($customerTransfer);
        $this->addTaggedCommentsParameter($taggedComments);
        $this->addAvailableCommentTags();
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
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return void
     */
    protected function addCustomerParameter(?CustomerTransfer $customerTransfer): void
    {
        $this->addParameter(static::PARAMETER_CUSTOMER, $customerTransfer);
    }

    /**
     * @return void
     */
    protected function addAvailableCommentTags(): void
    {
        $this->addParameter(
            static::PARAMETER_AVAILABLE_COMMENT_TAGS,
            $this->getFactory()->getCommentClient()->getAvailableCommentTags(),
        );
    }

    /**
     * @param array<string, array<\Generated\Shared\Transfer\CommentTransfer>> $taggedComments
     *
     * @return void
     */
    protected function addTaggedCommentsParameter(array $taggedComments): void
    {
        $this->addParameter(static::PARAMETER_TAGGED_COMMENTS, $taggedComments);
    }
}
