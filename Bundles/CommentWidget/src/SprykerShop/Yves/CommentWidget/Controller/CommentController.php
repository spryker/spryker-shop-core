<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentController extends CommentWidgetAbstractController
{
    protected const GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH = 'comment.validation.error.invalid_message_length';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request): RedirectResponse
    {
        $response = $this->executeAddAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request): RedirectResponse
    {
        $response = $this->executeUpdateAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request): RedirectResponse
    {
        $response = $this->executeRemoveAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeAddAction(Request $request): RedirectResponse
    {
        $returnUrl = $request->request->get(static::PARAMETER_RETURN_URL);
        $commentTransfer = $this->createCommentTransferFromRequest($request);

        if (!$this->getFactory()->createCommentChecker()->checkCommentMessageLength($commentTransfer)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH);

            return $this->redirectResponseExternal($returnUrl);
        }

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->fromArray($request->request->all(), true)
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->addComment($commentRequestTransfer);

        $this->executeCommentThreadAfterSuccessfulOperation($commentThreadResponseTransfer);
        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->redirectResponseExternal($returnUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction(Request $request): RedirectResponse
    {
        $returnUrl = $request->request->get(static::PARAMETER_RETURN_URL);
        $commentTransfer = $this->createCommentTransferFromRequest($request);

        if (!$this->getFactory()->createCommentChecker()->checkCommentMessageLength($commentTransfer)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH);

            return $this->redirectResponseExternal($returnUrl);
        }

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->updateComment($commentRequestTransfer);

        $this->executeCommentThreadAfterSuccessfulOperation($commentThreadResponseTransfer);
        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->redirectResponseExternal($returnUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeRemoveAction(Request $request): RedirectResponse
    {
        $returnUrl = $request->request->get(static::PARAMETER_RETURN_URL);
        $commentTransfer = $this->createCommentTransferFromRequest($request);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->removeComment($commentRequestTransfer);

        $this->executeCommentThreadAfterSuccessfulOperation($commentThreadResponseTransfer);
        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->redirectResponseExternal($returnUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function createCommentTransferFromRequest(Request $request): CommentTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $commentTransfer = (new CommentTransfer())
            ->fromArray($request->request->all(), true)
            ->setCustomer($customerTransfer);

        return $commentTransfer;
    }
}
