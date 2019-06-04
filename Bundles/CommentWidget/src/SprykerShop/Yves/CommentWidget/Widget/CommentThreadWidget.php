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
    protected const PARAMETER_OWNER_ID = 'ownerId';
    protected const PARAMETER_OWNER_TYPE = 'ownerType';
    protected const PARAMETER_RETURN_ROUTE = 'returnRoute';
    protected const PARAMETER_COMMENT_THREAD = 'commentThread';

    protected const PARAMETER_CREATE_COMMENT_FORM = 'createCommentForm';
    protected const PARAMETER_UPDATE_COMMENT_FORMS = 'updateCommentForms';

    /**
     * @param int $ownerId
     * @param string $ownerType
     * @param string $returnRoute
     * @param \Generated\Shared\Transfer\CommentThreadTransfer|null $commentThreadTransfer
     */
    public function __construct(
        int $ownerId,
        string $ownerType,
        string $returnRoute,
        ?CommentThreadTransfer $commentThreadTransfer
    ) {
        $commentThreadTransfer = $this->getFactory()
            ->createCommentFormDataProvider()
            ->getData($commentThreadTransfer);

        $this->addOwnerIdParameter($ownerId);
        $this->addOwnerTypeParameter($ownerType);
        $this->addReturnRouteParameter($returnRoute);
        $this->addCommentThreadParameter($commentThreadTransfer);
        $this->addUpdateCommentFormsParameter($commentThreadTransfer);
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
     * @param string $returnRoute
     *
     * @return void
     */
    protected function addReturnRouteParameter(string $returnRoute): void
    {
        $this->addParameter(static::PARAMETER_RETURN_ROUTE, $returnRoute);
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
    protected function addUpdateCommentFormsParameter(CommentThreadTransfer $commentThreadTransfer): void
    {
        $updateCommentForms = [];
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        foreach ($commentThreadTransfer->getComments() as $commentTransfer) {
            if ($customerTransfer->getIdCustomer() === $commentTransfer->getCustomer()->getIdCustomer()) {
                $updateCommentForms[$commentTransfer->getIdComment()] = $this->getFactory()->getCommentForm($commentTransfer)->createView();
            }
        }

        $this->addParameter(static::PARAMETER_UPDATE_COMMENT_FORMS, $updateCommentForms);
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
