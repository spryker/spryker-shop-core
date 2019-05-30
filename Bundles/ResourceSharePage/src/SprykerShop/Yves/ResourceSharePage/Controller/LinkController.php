<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Controller;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(string $resourceShareUuid, Request $request): RedirectResponse
    {
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setResourceShare((new ResourceShareTransfer())
                ->setUuid($resourceShareUuid))
            ->setCustomer($this->getFactory()
                ->getCustomerClient()
                ->getCustomer());

        $resourceShareResponseTransfer = $this->getFactory()->createResourceShareActivator()
            ->activateResourceShare($resourceShareRequestTransfer);

        $this->processMessages($resourceShareResponseTransfer);

        if (!$resourceShareResponseTransfer->getIsSuccessful() && !$resourceShareResponseTransfer->getIsLoginRequired()) {
            throw new NotFoundHttpException();
        }

        $routeTransfer = $this->getFactory()->createRouteResolver()
            ->resolveRoute(
                $request,
                (bool)$resourceShareResponseTransfer->getIsLoginRequired(),
                (new ResourceShareRequestTransfer())
                    ->setResourceShare($resourceShareResponseTransfer->getResourceShare())
                    ->setCustomer($this->getFactory()->getCustomerClient()->getCustomer())
            );

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
}
