<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use SprykerShop\Yves\PaymentAppWidget\Form\ExpressCheckoutForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 */
class ExpressCheckoutPreOrderController extends ExpressCheckoutAbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_KEY_CONTENT = 'content';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_CSRF_TOKEN = 'csrfToken';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_QUOTE_IS_EMPTY = 'payment_app_widget.validation.quote_is_empty';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function preOrderAction(Request $request): JsonResponse
    {
        $requestPayload = $request->toArray();
        $expressCheckoutForm = $this->createExpressCheckoutForm($requestPayload);

        $expressCheckoutForm->submit($requestPayload);

        if (!$expressCheckoutForm->isSubmitted() || !$expressCheckoutForm->isValid()) {
            return $this->createErrorJsonResponse(static::GLOSSARY_KEY_FORM_CSRF_VALIDATION_ERROR);
        }

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if ($quoteTransfer->getItems()->count() === 0) {
            return $this->createErrorJsonResponse(static::GLOSSARY_KEY_VALIDATION_QUOTE_IS_EMPTY);
        }

        $paymentTransfer = $this->getFactory()->createPaymentMapper()
            ->mapToPaymentTransfer($expressCheckoutForm->getData(), $quoteTransfer, new PaymentTransfer());

        $quoteTransfer->setPayment($paymentTransfer);
        $quoteTransfer->setPayments(new ArrayObject([$paymentTransfer]));

        $preOrderPaymentResponseTransfer = $this->getFactory()->createPreOrderPaymentInitializer()
            ->initializePreOrderPayment($paymentTransfer, $quoteTransfer);

        if (!$preOrderPaymentResponseTransfer->getIsSuccessfulOrFail()) {
            $this->getLogger()->error('Pre-order payment failed', ['response' => $preOrderPaymentResponseTransfer->toArray()]);

            return $this->createErrorJsonResponse(static::GLOSSARY_KEY_PAYMENT_FAILED);
        }

        $this->getFactory()->createQuoteUpdater()->updateQuoteWithPaymentData($quoteTransfer, $paymentTransfer, $preOrderPaymentResponseTransfer);

        /** @var string $csrfTokenName */
        $csrfTokenName = $requestPayload[ExpressCheckoutForm::OPTION_CSRF_TOKEN_NAME] ?? '';

        return $this->createSuccessPreOrderJsonResponse($preOrderPaymentResponseTransfer, $csrfTokenName);
    }

    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
     * @param string $csrfTokenName
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createSuccessPreOrderJsonResponse(
        PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer,
        string $csrfTokenName
    ): JsonResponse {
        return $this->jsonResponse([
            static::RESPONSE_KEY_CONTENT => $preOrderPaymentResponseTransfer->getPreOrderPaymentData(),
            static::RESPONSE_KEY_CSRF_TOKEN => $this->getFactory()->getCsrfTokenManager()->getToken($csrfTokenName)->getValue(),
        ]);
    }

    /**
     * @param array<string, mixed> $requestPayload
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createExpressCheckoutForm(array $requestPayload): FormInterface
    {
        $expressCheckoutFormDataProvider = $this->getFactory()->createExpressCheckoutFormDataProvider();

        return $this->getFactory()->getExpressCheckoutForm(
            null,
            $expressCheckoutFormDataProvider->getOptions($requestPayload),
        );
    }
}
