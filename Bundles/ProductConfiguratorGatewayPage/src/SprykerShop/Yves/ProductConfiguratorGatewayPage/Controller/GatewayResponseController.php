<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Controller;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory getFactory()
 */
class GatewayResponseController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        return $this->executeIndexAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request): RedirectResponse
    {
        $productConfigurationResponseTransfer = $this->getFactory()
            ->createProductConfiguratorResponseDataMapper()
            ->mapRequestToProductConfiguratorResponse($request, new ProductConfiguratorResponseTransfer());

        $productConfiguratorResponseProcessorResponseTransfer = $this->getFactory()
            ->getProductConfigurationClient()
            ->processProductConfiguratorResponse($productConfigurationResponseTransfer, $request->request->all());

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            $this->handleResponseErrors($productConfiguratorResponseProcessorResponseTransfer);

            // TODO: use correct $path
            return $this->redirectResponseInternal('');
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer): void
    {
        foreach ($productConfiguratorResponseProcessorResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
