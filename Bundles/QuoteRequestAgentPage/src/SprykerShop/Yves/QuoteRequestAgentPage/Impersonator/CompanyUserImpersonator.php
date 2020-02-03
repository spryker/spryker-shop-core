<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Impersonator;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface;

class CompanyUserImpersonator implements CompanyUserImpersonatorInterface
{
    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';
    protected const PARAM_SWITCH_USER = '_switch_user';

    protected const GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART = 'quote_request_page.quote_request.converted_to_cart';

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface
     */
    protected $quoteRequestAgentClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface $messengerClient
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface $quoteRequestAgentClient
     */
    public function __construct(
        QuoteRequestAgentPageToMessengerClientInterface $messengerClient,
        QuoteRequestAgentPageToQuoteRequestAgentClientInterface $quoteRequestAgentClient
    ) {
        $this->messengerClient = $messengerClient;
        $this->quoteRequestAgentClient = $quoteRequestAgentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return array
     */
    public function getImpersonationCompanyUserExitParams(QuoteRequestTransfer $quoteRequestTransfer, ?CompanyUserTransfer $companyUserTransfer): array
    {
        if ($companyUserTransfer && $companyUserTransfer->getIdCompanyUser() !== $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser()) {
            return [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestTransfer->getQuoteRequestReference(),
                static::PARAM_SWITCH_USER => '_exit',
            ];
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return array
     */
    public function getImpersonationCompanyUserEmailParams(QuoteRequestTransfer $quoteRequestTransfer, ?CompanyUserTransfer $companyUserTransfer): array
    {
        if (!$companyUserTransfer) {
            return [
                static::PARAM_SWITCH_USER => $quoteRequestTransfer->getCompanyUser()->getCustomer()->getEmail(),
            ];
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer, QuoteTransfer $quoteTransfer): void
    {
        if ($quoteTransfer->getQuoteRequestReference() !== $quoteRequestTransfer->getQuoteRequestReference()) {
            $quoteResponseTransfer = $this->quoteRequestAgentClient->convertQuoteRequestToQuote($quoteRequestTransfer);

            if ($quoteResponseTransfer->getIsSuccessful()) {
                $this->messengerClient->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART);
            }

            $this->handleQuoteResponseErrors($quoteResponseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    protected function handleQuoteResponseErrors(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $this->messengerClient->addErrorMessage($quoteErrorTransfer->getMessage());
        }
    }
}
