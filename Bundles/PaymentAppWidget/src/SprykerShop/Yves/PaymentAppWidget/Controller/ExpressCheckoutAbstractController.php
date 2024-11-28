<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Controller;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 */
abstract class ExpressCheckoutAbstractController extends AbstractController
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const REQUEST_KEY_CSRF_TOKEN_NAME = 'csrfTokenName';

    /**
     * @var string
     */
    protected const REQUEST_KEY_CSRF_TOKEN = 'csrfToken';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_REDIRECT_URL = 'redirectUrl';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_MESSAGES = 'messages';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INCORRECT_QUOTE = 'payment_app_widget.error.incorrect_quote';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PAYMENT_FAILED = 'payment_app_widget.error.payment_failed';

    /**
     * @param string|null $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createSuccessJsonResponse(
        ?string $redirectUrl = null
    ): JsonResponse {
        return $this->jsonResponse([
            static::RESPONSE_KEY_REDIRECT_URL => $redirectUrl,
        ]);
    }

    /**
     * @param string|null $errorMessage
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createErrorJsonResponse(?string $errorMessage = null): JsonResponse
    {
        if ($errorMessage) {
            $this->addErrorMessage($errorMessage);
        }

        return $this->jsonResponse([
            static::RESPONSE_KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function cleanQuotePayments(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer
            ->setPayment(null)
            ->setPayments(new ArrayObject())
            ->setPreOrderPaymentData([]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\Security\Csrf\CsrfToken
     */
    protected function getCsrfToken(Request $request): CsrfToken
    {
        $requestPayload = $request->toArray();

        /** @var string $csrfTokenName */
        $csrfTokenName = $requestPayload[static::REQUEST_KEY_CSRF_TOKEN_NAME] ?? '';

        /** @var string $csrfToken */
        $csrfToken = $requestPayload[static::REQUEST_KEY_CSRF_TOKEN] ?? '';

        return new CsrfToken($csrfTokenName, $csrfToken);
    }

    /**
     * @param string|null $redirectRouteName
     *
     * @return string|null
     */
    protected function getRedirectUrl(?string $redirectRouteName): ?string
    {
        $redirectUrl = null;
        if ($redirectRouteName) {
            $redirectUrl = $this->getRouter()->generate($redirectRouteName);
        }

        return $redirectUrl;
    }
}
