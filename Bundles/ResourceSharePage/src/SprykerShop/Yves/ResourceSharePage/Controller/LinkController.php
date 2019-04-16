<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Controller;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ResourceSharePage\ResourceSharePageFactory getFactory()
 */
class LinkController extends AbstractController
{
    /**
     * @param string $resourceShareUuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(string $resourceShareUuid, Request $request)
    {
        $response = $this->executeIndexAction($resourceShareUuid, $request);

        return $response;
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
            //TODO handle errors
            return [
                'errors' => 'something.went.wrong',
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
                'errors' => 'something.went.wrong',
            ];
        }

        return $this->redirectResponseInternal($routeTransfer->getRoute(), $routeTransfer->getParameters());
    }
}
