<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Controller;

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
    public const PARAM_ITEM_GROUP_KEY = 'item-group-key';
    public const PARAM_SOURCE_TYPE = 'source-type';
    protected const PARAM_REFERER = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $productConfiguratorRequestDataTransfer = $this->validateRequestOrThrowException($request);
        $refererUrl = $request->headers->get(static::PARAM_REFERER);

        $productConfiguratorRequestDataTransfer->setBackUrl($refererUrl)
            ->setSubmitUrl($this->getRouter()->generate(
                ProductConfiguratorGatewayPageRouteProviderPlugin::ROUTE_NAME_PRODUCT_CONFIGURATION_GATEWAY_RESPONSE
            )
        );

        $productConfiguratorRedirectTransfer = $this->getFactory()->createProductConfiguratorRedirectResolver()
            ->resolveProductConfiguratorRedirect($productConfiguratorRequestDataTransfer);

        if ($productConfiguratorRedirectTransfer->getIsSuccessful()) {
            return $this->redirectResponseExternal($productConfiguratorRedirectTransfer->getConfiguratorRedirectUrl());
        }

        foreach ($productConfiguratorRedirectTransfer->getMessages() as $message) {
            $this->addErrorMessage($message);
        }

        return $this->redirectResponseInternal($refererUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \SprykerShop\Yves\ProductConfiguratorGatewayPage\Exception\MissedPropertyException
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    protected function validateRequestOrThrowException(Request $request): ProductConfiguratorRequestDataTransfer
    {
        $form = $this->getFactory()
            ->getConfiguratorStateForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $errorList = [];

            foreach ($form->getErrors() as $error) {
                $errorList[] = $error->getMessage();
            }

            throw new MissedPropertyException(implode(PHP_EOL, $errorList));
        }

        return $form->getData();
    }
}
