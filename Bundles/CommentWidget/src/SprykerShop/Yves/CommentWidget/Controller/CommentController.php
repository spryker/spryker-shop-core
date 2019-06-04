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
class CommentController extends AbstractCommentController
{
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

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer)
            ->setOwnerId($request->request->get(static::PARAMETER_OWNER_ID))
            ->setOwnerType($request->request->get(static::PARAMETER_OWNER_TYPE));

        $commentThreadRequestTransfer = $this->getFactory()
            ->getCommentClient()
            ->addComment($commentRequestTransfer);

        if ($commentThreadRequestTransfer->getIsSuccessful()) {
            $this->executeCommentThreadAfterOperationPlugins($commentThreadRequestTransfer->getCommentThread());
        }

        return $this->redirectResponseInternal($request->request->get(static::PARAMETER_RETURN_ROUTE));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction(Request $request): RedirectResponse
    {
        $commentTransfer = $this->createCommentTransferFromRequest($request);

        $commentThreadRequestTransfer = $this->getFactory()
            ->getCommentClient()
            ->updateComment((new CommentRequestTransfer())->setComment($commentTransfer));

        if ($commentThreadRequestTransfer->getIsSuccessful()) {
            $this->executeCommentThreadAfterOperationPlugins($commentThreadRequestTransfer->getCommentThread());
        }

        return $this->redirectResponseInternal($request->request->get(static::PARAMETER_RETURN_ROUTE));
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
            $this->executeCommentThreadAfterOperationPlugins($commentThreadRequestTransfer->getCommentThread());
        }

        return $this->redirectResponseInternal($request->request->get(static::PARAMETER_RETURN_ROUTE));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function createCommentTransferFromRequest(Request $request): CommentTransfer
    {
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $this->getFactory()
            ->getCommentForm(new CommentTransfer())
            ->handleRequest($request)
            ->getData();

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $commentTransfer->setCustomer($customerTransfer);

        return $commentTransfer;
    }
}
