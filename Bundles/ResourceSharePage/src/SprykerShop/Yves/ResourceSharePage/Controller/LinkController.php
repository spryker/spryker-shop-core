<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ResourceSharePage\ResourceSharePageFactory getFactory()
 */
class LinkController extends AbstractController
{
    protected const MESSAGE_RESOURCE_SHARE_NO_ROUTE = 'resource-share.link.error.no-route';

    /**
     * @see \SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider::ROUTE_LOGIN
     */
    protected const ROUTE_LOGIN = 'login';
    protected const BACK_TO_LINK_REDIRECT = 'backToLinkRedirect';

    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(string $resourceShareUuid, Request $request)
    {
        $response = $this->executeIndexAction($resourceShareUuid, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            [],
            '@ResourceSharePage/views/link/index.twig'
        );
    }

    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(string $resourceShareUuid, Request $request)
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $loginLink = '';

        /** @var \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer */
        $resourceShareResponseTransfer = $this->getFactory()
            ->getResourceShareClient()
            ->activateResourceShare(
                (new ResourceShareRequestTransfer())
                    ->setCustomer($customerTransfer)
                    ->setUuid($resourceShareUuid)
            );

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return [
                'messages' => $resourceShareResponseTransfer->getErrorMessages(),
                'loginLink' => $loginLink,
            ];
        }

        $routeTransfer = $this->getFactory()
            ->getRouteResolver()
            ->resolveRoute($resourceShareResponseTransfer);

        if (!$routeTransfer) {
            return [
                'messages' => $this->createNoRouteMessage(),
                'loginLink' => $loginLink,
            ];
        }

        if ($resourceShareResponseTransfer->getIsLoginRequired()) {
            return [
                'messages' => $resourceShareResponseTransfer->getErrorMessages(),
                'loginLink' => $this->getApplication()->path(static::ROUTE_LOGIN, [
                    static::BACK_TO_LINK_REDIRECT => $this->getApplication()->path(
                        $routeTransfer->getRoute(),
                        $routeTransfer->getParameters()
                    ),
                ]),
            ];
        }

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return [
                'messages' => $resourceShareResponseTransfer->getErrorMessages(),
                'loginLink' => $loginLink,
            ];
        }

        return $this->redirectResponseInternal($routeTransfer->getRoute(), $routeTransfer->getParameters());
    }

    /**
     * @return \ArrayObject
     */
    protected function createNoRouteMessage(): ArrayObject
    {
        $message = (new MessageTransfer())
            ->setValue(static::MESSAGE_RESOURCE_SHARE_NO_ROUTE);

        $messages = (new ArrayObject());
        $messages->append($message);

        return $messages;
    }
}
