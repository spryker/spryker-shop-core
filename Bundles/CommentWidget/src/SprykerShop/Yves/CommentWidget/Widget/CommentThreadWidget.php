<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Widget;

use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentThreadWidget extends AbstractWidget
{
    protected const PARAMETER_RETURN_URL = 'returnUrl';
    protected const PARAMETER_COMMENT_THREAD = 'commentThread';

    protected const PARAMETER_ALL_COMMENT_FORMS = 'allCommentForms';
    protected const PARAMETER_ATTACH_COMMENT_FORMS = 'attachCommentForms';
    protected const PARAMETER_CREATE_COMMENT_FORM = 'createCommentForm';

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
        $commentThreadTransfer = $this->getFactory()
            ->createCommentFormDataProvider()
            ->getData($commentThreadTransfer);

        $commentThreadTransfer
            ->setOwnerId($ownerId)
            ->setOwnerType($ownerType);

        $this->addReturnUrlParameter($returnUrl);
        $this->addCommentThreadParameter($commentThreadTransfer);
        $this->addALlCommentFormsParameter($commentThreadTransfer);
        $this->addAttachCommentFormsParameter($commentThreadTransfer);
        $this->addCreateCommentFormParameter();
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
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return void
     */
    protected function addAllCommentFormsParameter(CommentThreadTransfer $commentThreadTransfer): void
    {
        $allCommentForms = [];
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        foreach ($commentThreadTransfer->getComments() as $commentTransfer) {
            if ($customerTransfer->getIdCustomer() === $commentTransfer->getCustomer()->getIdCustomer()) {
                $allCommentForms[$commentTransfer->getIdComment()] = $this->getFactory()->getCommentForm($commentTransfer)->createView();
            }
        }

        $this->addParameter(static::PARAMETER_ALL_COMMENT_FORMS, $allCommentForms);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return void
     */
    protected function addAttachCommentFormsParameter(CommentThreadTransfer $commentThreadTransfer): void
    {
        $attachCommentForms = [];
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        foreach ($commentThreadTransfer->getComments() as $commentTransfer) {
            if ($customerTransfer->getIdCustomer() === $commentTransfer->getCustomer()->getIdCustomer()
            && $commentTransfer->getIsAttached()) {
                $attachCommentForms[$commentTransfer->getIdComment()] = $this->getFactory()->getCommentForm($commentTransfer)->createView();
            }
        }

        $this->addParameter(static::PARAMETER_ATTACH_COMMENT_FORMS, $attachCommentForms);
    }

    /**
     * @return void
     */
    protected function addCreateCommentFormParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_CREATE_COMMENT_FORM,
            $this->getFactory()->getCommentForm(new CommentTransfer())->createView()
        );
    }
}
