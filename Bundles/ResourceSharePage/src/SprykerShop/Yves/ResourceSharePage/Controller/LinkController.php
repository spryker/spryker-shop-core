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
        /** @var \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer */
        $resourceShareResponseTransfer = $this->getFactory()
            ->getResourceShareClient()
            ->activateResourceShare(
                (new ResourceShareRequestTransfer())
                    ->setUuid($resourceShareUuid)
            );

        if (!$resourceShareResponseTransfer->getIsSuccessful()) {
            return [
                'messages' => $resourceShareResponseTransfer->getErrorMessages(),
            ];
        }

        $routeTransfer = null;

        foreach ($this->getFactory()->getResourceShareRouterStrategyPlugin() as $strategyPlugin) {
            if (!$strategyPlugin->isApplicable($resourceShareResponseTransfer->getResourceShare())) {
                continue;
            }

            $routeTransfer = $strategyPlugin->resolveRoute($resourceShareResponseTransfer->getResourceShare());
        }

        if (!$routeTransfer) {
            return [
                'messages' => $this->createNoRouteMessage(),
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
