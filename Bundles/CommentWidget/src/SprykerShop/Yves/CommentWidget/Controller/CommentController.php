<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCommentAction(Request $request): Response
    {
        $response = $this->executeAddCommentAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateCommentAction(Request $request): Response
    {
        $response = $this->executeUpdateCommentAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeAddCommentAction(Request $request): Response
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $commentTransfer = (new CommentTransfer())
            ->setCustomer($customerTransfer)
            ->setMessage($request->request->get('message'));

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer)
            ->setOwnerId($request->request->get('ownerId'))
            ->setOwnerType($request->request->get('ownerType'));

        $this->getFactory()
            ->getCommentClient()
            ->addComment($commentRequestTransfer);

        $commentThreadTransfer = $this->getFactory()
            ->getCommentClient()
            ->findCommentThread($commentRequestTransfer);

        return $this->renderView(
            '@CommentWidget/views/comment/comment.twig',
            [
                'commentThread' => $commentThreadTransfer,
            ]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeUpdateCommentAction(Request $request): Response
    {
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($request->request->get('ownerId'))
            ->setOwnerType($request->request->get('ownerType'));

        $commentThreadTransfer = $this->getFactory()
            ->getCommentClient()
            ->findCommentThread($commentRequestTransfer);

        return $this->renderView(
            '@CommentWidget/views/comment/comment.twig',
            [
                'commentThread' => $commentThreadTransfer,
            ]
        );
    }
}
