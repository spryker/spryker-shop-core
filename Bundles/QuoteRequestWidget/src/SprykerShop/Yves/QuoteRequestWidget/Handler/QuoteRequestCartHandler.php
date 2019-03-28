<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Handler;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCompanyUserClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuoteRequestCartHandler implements QuoteRequestCartHandlerInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @var \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface $quoteRequestClient
     * @param \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCompanyUserClientInterface $companyUserClient
     */
    public function __construct(
        QuoteRequestWidgetToQuoteClientInterface $quoteClient,
        QuoteRequestWidgetToQuoteRequestClientInterface $quoteRequestClient,
        QuoteRequestWidgetToCompanyUserClientInterface $companyUserClient
    ) {
        $this->quoteClient = $quoteClient;
        $this->quoteRequestClient = $quoteRequestClient;
        $this->companyUserClient = $companyUserClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequestQuote(): QuoteRequestResponseTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if (!$quoteTransfer->getQuoteRequestReference()) {
            return $this->getErrorResponse();
        }

        $companyUserTransfer = $this->companyUserClient->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException("Only company users are allowed to access this page");
        }

        $quoteRequestTransfer = $this->quoteRequestClient->findCompanyUserQuoteRequestByReference(
            $quoteTransfer->getQuoteRequestReference(),
            $companyUserTransfer->getIdCompanyUser()
        );

        if (!$quoteRequestTransfer) {
            return $this->getErrorResponse();
        }

        $quoteRequestTransfer->getLatestVersion()->setQuote($quoteTransfer);

        return $this->quoteRequestClient->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getErrorResponse(): QuoteRequestResponseTransfer
    {
        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage((new MessageTransfer())->setValue(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS));
    }
}
