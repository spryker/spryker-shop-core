<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Controller;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
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
    /**
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_NAME_HOME
     * @var string
     */
    protected const FALLBACK_ROUTE_NAME = 'home';

    /**
     * @var string
     */
    protected const REQUEST_HEADER_REFERER = 'referer';

    /**
     * @uses \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL
     * @var int
     */
    protected const ABSOLUTE_URL = 0;

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
        $refererUrl = $request->headers->get(static::REQUEST_HEADER_REFERER) ?? static::FALLBACK_ROUTE_NAME;

        $productConfiguratorRequestDataTransfer->setBackUrl($refererUrl)
            ->setSubmitUrl($this->getRouter()->generate(
                ProductConfiguratorGatewayPageRouteProviderPlugin::ROUTE_NAME_PRODUCT_CONFIGURATOR_GATEWAY_RESPONSE,
                [],
                static::ABSOLUTE_URL,
            ));

        $productConfiguratorRequestTransfer = $this->getFactory()->getProductConfigurationClient()->expandProductConfiguratorRequestWithContextData(
            (new ProductConfiguratorRequestTransfer())->setProductConfiguratorRequestData($productConfiguratorRequestDataTransfer),
        );

        $productConfiguratorRedirectTransfer = $this->getFactory()
            ->createProductConfiguratorRedirectResolver()
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);

        if ($productConfiguratorRedirectTransfer->getIsSuccessful()) {
            return $this->redirectResponseExternal($productConfiguratorRedirectTransfer->getConfiguratorRedirectUrlOrFail());
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
    ): void {
        foreach ($productConfiguratorRedirectTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValueOrFail());
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
        $productConfiguratorRequestDataForm = $this->getFactory()->getProductConfiguratorRequestDataForm(
            $this->getFactory()->createProductConfiguratorRequestDataFormDataProvider()->getOptions($request),
        );

        $productConfiguratorRequestDataForm->handleRequest($request);

        if (!$productConfiguratorRequestDataForm->isSubmitted() || !$productConfiguratorRequestDataForm->isValid()) {
            $errorList = [];

            foreach ($productConfiguratorRequestDataForm->getErrors(true) as $error) {
                $errorList[] = $error->getMessage();
            }

            throw new MissedPropertyException(implode(PHP_EOL, $errorList));
        }

        return $productConfiguratorRequestDataForm->getData();
    }
}
