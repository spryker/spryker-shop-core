<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentAsyncController extends CommentWidgetAbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAction(Request $request): JsonResponse
    {
        if (!$this->validateCsrfTokenInRequest($request, static::CSRF_TOKEN_ID_ADD_COMMENT_FORM)) {
            return $this->getMessagesJsonResponse();
        }

        $commentTransfer = $this->createCommentTransferFromRequest($request);

        if (!$this->validateComment($commentTransfer)) {
            return $this->getMessagesJsonResponse();
        }

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->fromArray($request->request->all(), true)
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->addComment($commentRequestTransfer);

        return $this->getCommentThreadResponse($commentThreadResponseTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request): JsonResponse
    {
        if (!$this->validateCsrfTokenInRequest($request, static::CSRF_TOKEN_ID_UPDATE_COMMENT_FORM)) {
            return $this->getMessagesJsonResponse();
        }

        $commentTransfer = $this->createCommentTransferFromRequest($request);

        if (!$this->validateComment($commentTransfer)) {
            return $this->getMessagesJsonResponse();
        }

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->updateComment($commentRequestTransfer);

        return $this->getCommentThreadResponse($commentThreadResponseTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Request $request): JsonResponse
    {
        if (!$this->validateCsrfTokenInRequest($request, static::CSRF_TOKEN_ID_UPDATE_COMMENT_FORM)) {
            return $this->getMessagesJsonResponse();
        }

        $commentTransfer = $this->createCommentTransferFromRequest($request);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->removeComment($commentRequestTransfer);

        return $this->getCommentThreadResponse($commentThreadResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return bool
     */
    protected function validateComment(CommentTransfer $commentTransfer): bool
    {
        if (!$this->getFactory()->createCommentChecker()->checkCommentMessageLength($commentTransfer)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH);

            return false;
        }

        return true;
    }
}
