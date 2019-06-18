<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use SprykerShop\Yves\CommentWidget\Form\CommentForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentController extends AbstractController
{
    protected const PARAMETER_UUID = 'uuid';
    protected const PARAMETER_RETURN_URL = 'returnUrl';
    protected const PARAMETER_OWNER_ID = 'ownerId';
    protected const PARAMETER_OWNER_TYPE = 'ownerType';

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
        $commentTransfer = $this->createCommentTransferFromRequest($request);

        if (!$this->validateCommentForm($request, $commentTransfer)) {
            return $this->redirectResponseExternal($request->request->get(static::PARAMETER_RETURN_URL));
        }

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer)
            ->setOwnerId($request->request->get(static::PARAMETER_OWNER_ID))
            ->setOwnerType($request->request->get(static::PARAMETER_OWNER_TYPE));

        $commentThreadRequestTransfer = $this->getFactory()
            ->getCommentClient()
            ->addComment($commentRequestTransfer);

        if ($commentThreadRequestTransfer->getIsSuccessful()) {
            $this->getFactory()
                ->createCommentOperation()
                ->executeCommentThreadAfterOperationPlugins($commentThreadRequestTransfer->getCommentThread());
        }

        return $this->redirectResponseExternal($request->request->get(static::PARAMETER_RETURN_URL));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction(Request $request): RedirectResponse
    {
        $commentTransfer = $this->createCommentTransferFromRequest($request);

        if (!$this->validateCommentForm($request, $commentTransfer)) {
            return $this->redirectResponseExternal($request->request->get(static::PARAMETER_RETURN_URL));
        }

        $commentThreadRequestTransfer = $this->getFactory()
            ->getCommentClient()
            ->updateComment((new CommentRequestTransfer())->setComment($commentTransfer));

        if ($commentThreadRequestTransfer->getIsSuccessful()) {
            $this->getFactory()
                ->createCommentOperation()
                ->executeCommentThreadAfterOperationPlugins($commentThreadRequestTransfer->getCommentThread());
        }

        return $this->redirectResponseExternal($request->request->get(static::PARAMETER_RETURN_URL));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeRemoveAction(Request $request): RedirectResponse
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $commentTransfer = (new CommentTransfer())
            ->setUuid($request->request->get(static::PARAMETER_UUID))
            ->setCustomer($customerTransfer);

        $commentThreadRequestTransfer = $this->getFactory()
            ->getCommentClient()
            ->removeComment((new CommentRequestTransfer())->setComment($commentTransfer));

        if ($commentThreadRequestTransfer->getIsSuccessful()) {
            $this->getFactory()
                ->createCommentOperation()
                ->executeCommentThreadAfterOperationPlugins($commentThreadRequestTransfer->getCommentThread());
        }

        return $this->redirectResponseExternal($request->request->get(static::PARAMETER_RETURN_URL));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CommentTransfer|null
     */
    protected function createCommentTransferFromRequest(Request $request): ?CommentTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $commentTransfer = (new CommentTransfer())
            ->fromArray($request->request->get(CommentForm::COMMENT_FORM), true)
            ->setCustomer($customerTransfer);

        return $commentTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return bool
     */
    protected function validateCommentForm(Request $request, CommentTransfer $commentTransfer): bool
    {
        $commentForm = $this->getFactory()
            ->getCommentForm($commentTransfer)
            ->handleRequest($request);

        $this->addFormErrorMessages($commentForm->getErrors(true));

        return $commentForm->isValid();
    }

    /**
     * @param \Symfony\Component\Form\FormErrorIterator $formErrorIterator
     *
     * @return void
     */
    protected function addFormErrorMessages(FormErrorIterator $formErrorIterator): void
    {
        if ($formErrorIterator->count() === 0) {
            return;
        }

        foreach ($formErrorIterator as $formError) {
            $this->addErrorMessage($formError->getMessage());
        }
    }
}
