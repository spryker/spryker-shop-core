<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTagTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use SprykerShop\Yves\CommentWidget\CommentWidgetConfig;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentTagController extends AbstractController
{
    protected const PARAMETER_UUID = 'uuid';
    protected const PARAMETER_RETURN_URL = 'returnUrl';

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
            ->addCommentTag((new CommentTagTransfer())->setName(CommentWidgetConfig::COMMENT_TAG_ATTACHED));

        $commentThreadRequestTransfer = $this->getFactory()
            ->getCommentClient()
            ->updateCommentTags((new CommentRequestTransfer())->setComment($commentTransfer));

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
            $this->getFactory()
                ->createCommentOperation()
                ->executeCommentThreadAfterOperationPlugins($commentThreadRequestTransfer->getCommentThread());
        }

        return $this->redirectResponseExternal($request->request->get(static::PARAMETER_RETURN_URL));
    }
}
