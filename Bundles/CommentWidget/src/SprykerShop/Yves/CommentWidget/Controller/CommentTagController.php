<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTagTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentTagController extends AbstractCommentController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function attachAction(Request $request): RedirectResponse
    {
        $response = $this->executeAttachAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unattachAction(Request $request): RedirectResponse
    {
        $response = $this->executeUnattachAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeAttachAction(Request $request): RedirectResponse
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $commentTransfer = (new CommentTransfer())
            ->setUuid($request->request->get(static::PARAMETER_UUID))
            ->setCustomer($customerTransfer)
            ->addCommentTag((new CommentTagTransfer())->setName('attached'));

        $commentThreadRequestTransfer = $this->getFactory()
            ->getCommentClient()
            ->updateCommentTags((new CommentRequestTransfer())->setComment($commentTransfer));

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
    protected function executeUnattachAction(Request $request): RedirectResponse
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $commentTransfer = (new CommentTransfer())
            ->setUuid($request->request->get(static::PARAMETER_UUID))
            ->setCustomer($customerTransfer);

        $commentThreadRequestTransfer = $this->getFactory()
            ->getCommentClient()
            ->updateCommentTags((new CommentRequestTransfer())->setComment($commentTransfer));

        if ($commentThreadRequestTransfer->getIsSuccessful()) {
            $this->executeCommentThreadAfterOperationPlugins($commentThreadRequestTransfer->getCommentThread());
        }

        return $this->redirectResponseInternal($request->request->get(static::PARAMETER_RETURN_ROUTE));
    }
}
