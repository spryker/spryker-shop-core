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
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_NAME_HOME
     */
    protected const FALLBACK_ROUTE_NAME = 'home';

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

        if ($productConfiguratorResponseProcessorResponseTransfer->getMessages()->count() > 0) {
            $this->handleResponseErrors($productConfiguratorResponseProcessorResponseTransfer);
        }

        return $this->executeProductConfiguratorGatewayBackUrlResolverStrategyPlugins(
            $productConfiguratorResponseProcessorResponseTransfer->getProductConfiguratorResponseOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer): void
    {
        $errorsMessages = [];
        foreach ($productConfiguratorResponseProcessorResponseTransfer->getMessages() as $messageTransfer) {
            $errorsMessages[$messageTransfer->getValueOrFail()] = $messageTransfer->getParameters();
        }

        $translatedErrorMessages = $this->getFactory()
            ->getGlossaryStorageClient()
            ->translateBulk(
                array_keys($errorsMessages),
                $this->getLocale(),
                $errorsMessages
            );

        foreach ($translatedErrorMessages as $translatedErrorMessage) {
            $this->addErrorMessage($translatedErrorMessage);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeProductConfiguratorGatewayBackUrlResolverStrategyPlugins(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): RedirectResponse {
        foreach ($this->getFactory()->getProductConfiguratorGatewayBackUrlResolverStrategyPlugins() as $productConfiguratorGatewayBackUrlResolverStrategyPlugin) {
            if ($productConfiguratorGatewayBackUrlResolverStrategyPlugin->isApplicable($productConfiguratorResponseTransfer)) {
                return $this->redirectResponseExternal($productConfiguratorGatewayBackUrlResolverStrategyPlugin->resolveBackUrl($productConfiguratorResponseTransfer));
            }
        }

        return $this->redirectResponseInternal(static::FALLBACK_ROUTE_NAME);
    }
}
