<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\ResourceSharePage\ResourceSharePageFactory getFactory()
 */
class LinkController extends AbstractController
{
    /**
     * @see \SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider::ROUTE_LOGIN
     */
    protected const ROUTE_LOGIN = 'login';
    protected const LINK_REDIRECT_URL = 'LinkRedirectUrl';
    protected const ERROR_MESSAGE_SEPARATOR = '<BR>';

    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(string $resourceShareUuid, Request $request)
    {
        $response = $this->executeIndexAction($resourceShareUuid, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@ResourceSharePage/views/link/index.twig');
    }

    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(string $resourceShareUuid, Request $request)
    {
        $resourceShareResponseTransfer = $this->getFactory()->createResourceShareActivator()
            ->activateResourceShare($resourceShareUuid);

        $routeTransfer = $this->getFactory()->getRouteResolver()
            ->resolveRoute($resourceShareResponseTransfer);

        if ($resourceShareResponseTransfer->getIsLoginRequired()) {
            $this->addErrorMessage($resourceShareResponseTransfer->getMessages());

            return $this->redirectResponseInternal(static::ROUTE_LOGIN, [
                static::LINK_REDIRECT_URL => $this->getFactory()->getApplication()->path(
                    $routeTransfer->getRoute(),
                    $routeTransfer->getParameters()
                ),
            ]);
        }

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            $errorMessages = [];
            foreach ($resourceShareResponseTransfer->getMessages() as $messageTransfer) {
                $errorMessages[] = $messageTransfer->getValue();
            }

            throw new NotFoundHttpException(implode(static::ERROR_MESSAGE_SEPARATOR, $errorMessages));
        }

        return $this->redirectResponseInternal($routeTransfer->getRoute(), $routeTransfer->getParameters());
    }
}
