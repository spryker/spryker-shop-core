<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Controller;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Exception\MissedPropertyException;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\Router\ProductConfiguratorGatewayPageRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory getFactory()
 */
class GatewayRequestController extends AbstractController
{
    protected const REQUEST_HEADER_REFERER = 'referer';

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
        $productConfiguratorRequestDataTransfer = $this->validateProductConfiguratorRequestDataForm($request);
        $refererUrl = $request->headers->get(static::REQUEST_HEADER_REFERER);

        $productConfiguratorRequestDataTransfer->setBackUrl($refererUrl)
            ->setSubmitUrl($this->getRouter()->generate(
                ProductConfiguratorGatewayPageRouteProviderPlugin::ROUTE_NAME_PRODUCT_CONFIGURATOR_GATEWAY_RESPONSE
            ));

        $productConfiguratorRedirectTransfer = $this->getFactory()->createProductConfiguratorRedirectResolver()
            ->resolveProductConfiguratorRedirect($productConfiguratorRequestDataTransfer);

        if ($productConfiguratorRedirectTransfer->getIsSuccessful()) {
            return $this->redirectResponseExternal($productConfiguratorRedirectTransfer->getConfiguratorRedirectUrl());
        }

        $this->handleProductConfigurationRedirectErrors($productConfiguratorRedirectTransfer);

        return $this->redirectResponseExternal($refererUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer
     *
     * @return void
     */
    protected function handleProductConfigurationRedirectErrors(
        ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer
    ) {
        foreach ($productConfiguratorRedirectTransfer->getMessages() as $message) {
            $this->addErrorMessage($message);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \SprykerShop\Yves\ProductConfiguratorGatewayPage\Exception\MissedPropertyException
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    protected function validateProductConfiguratorRequestDataForm(Request $request): ProductConfiguratorRequestDataTransfer
    {
        $form = $this->getFactory()
            ->getProductConfiguratorRequestDataForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $errorList = [];

            foreach ($form->getErrors(true) as $error) {
                $errorList[] = $error->getMessage();
            }

            throw new MissedPropertyException(implode(PHP_EOL, $errorList));
        }

        return $form->getData();
    }
}
