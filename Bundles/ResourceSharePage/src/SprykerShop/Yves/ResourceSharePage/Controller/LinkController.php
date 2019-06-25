<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Controller;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\ResourceSharePage\ResourceSharePageFactory getFactory()
 */
class LinkController extends AbstractController
{
    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(string $resourceShareUuid, Request $request): RedirectResponse
    {
        $response = $this->executeIndexAction($resourceShareUuid, $request);

        return $response;
    }

    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(string $resourceShareUuid, Request $request): RedirectResponse
    {
        $resourceShareResponseTransfer = $this->activateResourceShare(
            $this->getResourceShareByUuid($resourceShareUuid)->getResourceShare()
        );
        $routeTransfer = $this->resolveRoute($request, (bool)$resourceShareResponseTransfer->getIsLoginRequired(), $resourceShareResponseTransfer->getResourceShare());

        return $this->redirectResponseInternal($routeTransfer->getRoute(), $routeTransfer->getParameters());
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return void
     */
    protected function processMessages(ResourceShareResponseTransfer $resourceShareResponseTransfer): void
    {
        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            foreach ($resourceShareResponseTransfer->getMessages() as $messageTransfer) {
                $this->addErrorMessage($messageTransfer->getValue());
            }

            return;
        }

        foreach ($resourceShareResponseTransfer->getMessages() as $messageTransfer) {
            $this->addSuccessMessage($messageTransfer->getValue());
        }
    }

    /**
     * @param string $resourceShareUuid
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function getResourceShareByUuid(string $resourceShareUuid): ResourceShareResponseTransfer
    {
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setResourceShare(
                (new ResourceShareTransfer())->setUuid($resourceShareUuid)
            );

        $resourceShareResponseTransfer = $this->getFactory()
            ->getResourceShareClient()
            ->getResourceShareByUuid($resourceShareRequestTransfer);

        $this->processMessages($resourceShareResponseTransfer);

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            throw new NotFoundHttpException();
        }

        return $resourceShareResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function activateResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setResourceShare($resourceShareTransfer)
            ->setCustomer(
                $this->getFactory()
                    ->getCustomerClient()
                    ->getCustomer()
            );

        $resourceShareResponseTransfer = $this->getFactory()
            ->createResourceShareActivator()
            ->activateResourceShare($resourceShareRequestTransfer);

        $this->processMessages($resourceShareResponseTransfer);

        if (!$resourceShareResponseTransfer->getIsSuccessful() && !$resourceShareResponseTransfer->getIsLoginRequired()) {
            throw new NotFoundHttpException();
        }

        return $resourceShareResponseTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param bool $isLoginRequired
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\RouteTransfer
     */
    protected function resolveRoute(Request $request, bool $isLoginRequired, ResourceShareTransfer $resourceShareTransfer): RouteTransfer
    {
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setResourceShare($resourceShareTransfer)
            ->setCustomer(
                $this->getFactory()
                    ->getCustomerClient()
                    ->getCustomer()
            );

        $routeTransfer = $this->getFactory()
            ->createRouteResolver()
            ->resolveRoute(
                $request,
                $isLoginRequired,
                $resourceShareRequestTransfer
            );

        return $routeTransfer;
    }
}
