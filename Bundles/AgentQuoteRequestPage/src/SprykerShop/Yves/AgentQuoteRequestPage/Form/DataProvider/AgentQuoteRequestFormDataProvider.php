<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Form\DataProvider;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface;
use SprykerShop\Yves\AgentQuoteRequestPage\Form\AgentQuoteRequestForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AgentQuoteRequestFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(AgentQuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient, Request $request)
    {
        $this->quoteRequestClient = $quoteRequestClient;
        $this->request = $request;
    }

    /**
     * @param string $agentQuoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function getData(string $agentQuoteRequestReference): QuoteRequestTransfer
    {
        return $this->getQuoteRequestTransferByReference($agentQuoteRequestReference);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return array
     */
    public function getOptions(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        return [
            AgentQuoteRequestForm::OPTION_VERSION_REFERENCE_CHOICES => $this->getVersionReferenceChoices($quoteRequestTransfer),
        ];
    }

    /**
     * @param string $quoteRequestReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getQuoteRequestTransferByReference(string $quoteRequestReference): QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setWithHidden(true);

        $quoteRequestTransfers = $this->quoteRequestClient
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer|null $quoteRequestTransfer */
        $quoteRequestTransfer = array_shift($quoteRequestTransfers);

        if (!$quoteRequestTransfer) {
            throw new NotFoundHttpException();
        }

        $quoteRequestTransfer->setActiveVersion($this->findActiveQuoteRequestVersion($quoteRequestTransfer));

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer|null
     */
    protected function findActiveQuoteRequestVersion(QuoteRequestTransfer $quoteRequestTransfer): ?QuoteRequestVersionTransfer
    {
        $versionReference = $this->request->query->get(AgentQuoteRequestForm::FIELD_QUOTE_REQUEST_VERSION_REFERENCE);

        if (!$versionReference || $versionReference === $quoteRequestTransfer->getLatestVersion()->getVersionReference()) {
            return $quoteRequestTransfer->getLatestVersion();
        }

        $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setQuoteRequestVersionReference($versionReference);

        $quoteRequestVersionTransfers = $this->quoteRequestClient
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer)
            ->getQuoteRequestVersions()
            ->getArrayCopy();

        $quoteRequestVersionTransfer = array_shift($quoteRequestVersionTransfers);

        return $quoteRequestVersionTransfer ?? $quoteRequestTransfer->getLatestVersion();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return array
     */
    protected function getVersionReferenceChoices(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        $versionReferences = [];

        foreach ($quoteRequestTransfer->getVersionReferences() as $versionReference) {
            $versionReferences[$versionReference] = $versionReference;
        }

        return $versionReferences;
    }
}
