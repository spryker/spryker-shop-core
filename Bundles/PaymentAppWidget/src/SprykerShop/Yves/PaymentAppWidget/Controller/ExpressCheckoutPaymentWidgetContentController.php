<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig getConfig()
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 */
class ExpressCheckoutPaymentWidgetContentController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_KEY_CONTENT = 'content';

    /**
     * @var string
     */
    protected const VIEW_DATA_KEY_EXPRESS_CHECKOUT_PAYMENT_METHOD_WIDGETS = 'expressCheckoutPaymentMethodWidgets';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $expressCheckoutPaymentWidgetContentViewData = $this->getExpressCheckoutPaymentWidgetContentViewData();

        return $this->jsonResponse([
            static::RESPONSE_KEY_CONTENT => $this->renderExpressCheckoutPaymentWidgetContent($expressCheckoutPaymentWidgetContentViewData),
        ]);
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer>> $expressCheckoutPaymentWidgetContentViewData
     *
     * @return string
     */
    protected function renderExpressCheckoutPaymentWidgetContent(array $expressCheckoutPaymentWidgetContentViewData): string
    {
        $response = $this->renderView(
            $this->getFactory()->getConfig()->getExpressCheckoutPaymentWidgetContentTemplatePath(),
            $expressCheckoutPaymentWidgetContentViewData,
        );

        return $response->getContent() ?: '';
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ExpressCheckoutPaymentMethodWidgetTransfer>>
     */
    protected function getExpressCheckoutPaymentWidgetContentViewData(): array
    {
        $expressCheckoutPaymentMethodWidgetResolver = $this->getFactory()->getExpressCheckoutPaymentMethodWidgetResolver();

        return [
            static::VIEW_DATA_KEY_EXPRESS_CHECKOUT_PAYMENT_METHOD_WIDGETS => $expressCheckoutPaymentMethodWidgetResolver->getExpressCheckoutPaymentMethodWidgets(),
        ];
    }
}
