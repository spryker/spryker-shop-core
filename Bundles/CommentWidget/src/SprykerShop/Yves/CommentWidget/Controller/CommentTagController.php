<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentTagController extends CommentWidgetAbstractController
{
    protected const PARAMETER_NAME = 'name';

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(string $uuid, Request $request): RedirectResponse
    {
        $response = $this->executeAddAction($uuid, $request);

        return $response;
    }

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(string $uuid, Request $request): RedirectResponse
    {
        $response = $this->executeRemoveAction($uuid, $request);

        return $response;
    }

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeAddAction(string $uuid, Request $request): RedirectResponse
    {
        $returnUrl = $request->query->get(static::PARAMETER_RETURN_URL);
        $name = $request->query->get(static::PARAMETER_NAME);

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setComment((new CommentTransfer())->setUuid($uuid))
            ->setName($name);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->addCommentTag($commentTagRequestTransfer);

        $this->executeCommentThreadAfterSuccessfulOperation($commentThreadResponseTransfer);
        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->redirectResponseExternal($returnUrl);
    }

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeRemoveAction(string $uuid, Request $request): RedirectResponse
    {
        $returnUrl = $request->query->get(static::PARAMETER_RETURN_URL);
        $name = $request->query->get(static::PARAMETER_NAME);

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setComment((new CommentTransfer())->setUuid($uuid))
            ->setName($name);

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentClient()
            ->removeCommentTag($commentTagRequestTransfer);

        $this->executeCommentThreadAfterSuccessfulOperation($commentThreadResponseTransfer);
        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->redirectResponseExternal($returnUrl);
    }
}
