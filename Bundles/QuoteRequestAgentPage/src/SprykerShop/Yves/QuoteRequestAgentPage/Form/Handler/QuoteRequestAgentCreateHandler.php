<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Form\Handler;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface;

class QuoteRequestAgentCreateHandler implements QuoteRequestAgentCreateHandlerInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND = 'quote_request.validation.error.company_user_not_found';

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface
     */
    protected $quoteRequestAgentClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface $quoteRequestAgentClient
     */
    public function __construct(QuoteRequestAgentPageToQuoteRequestAgentClientInterface $quoteRequestAgentClient)
    {
        $this->quoteRequestAgentClient = $quoteRequestAgentClient;
    }

    /**
     * @param int|null $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(?int $idCompanyUser): QuoteRequestResponseTransfer
    {
        if (!$idCompanyUser) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND);
        }

        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser((new CompanyUserTransfer())->setIdCompanyUser($idCompanyUser));

        return $this->quoteRequestAgentClient->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getErrorResponse(string $message): QuoteRequestResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
