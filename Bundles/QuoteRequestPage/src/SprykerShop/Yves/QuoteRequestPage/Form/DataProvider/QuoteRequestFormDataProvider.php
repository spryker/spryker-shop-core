<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form\DataProvider;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Form\QuoteRequestForm;
use SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuoteRequestFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig
     */
    protected $config;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface $companyUserClient
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient
     * @param \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig $config
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(
        QuoteRequestPageToCompanyUserClientInterface $companyUserClient,
        QuoteRequestPageToCartClientInterface $cartClient,
        QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient,
        QuoteRequestPageConfig $config,
        Request $request
    ) {
        $this->companyUserClient = $companyUserClient;
        $this->cartClient = $cartClient;
        $this->quoteRequestClient = $quoteRequestClient;
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * @param string|null $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function getData(?string $quoteRequestReference): QuoteRequestTransfer
    {
        if (!$quoteRequestReference) {
            return $this->createQuoteRequestTransfer();
        }

        return $this->getQuoteRequestTransferByReference($quoteRequestReference);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return array
     */
    public function getOptions(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        return [
            QuoteRequestForm::OPTION_VERSION_REFERENCE_CHOICES => $this->getVersionReferenceChoices($quoteRequestTransfer),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequestTransfer(): QuoteRequestTransfer
    {
        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setQuote($this->cartClient->getQuote());

        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setCompanyUser($this->companyUserClient->findCompanyUser())
            ->setCreatedAt((new DateTime())->format('Y-m-d H:i:s'))
            ->setStatus($this->config->getInitialStatus())
            ->setLatestVersion($quoteRequestVersionTransfer);

        return $quoteRequestTransfer;
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
            ->setCompanyUser($this->companyUserClient->findCompanyUser());

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
        $versionReference = $this->request->query->get(QuoteRequestForm::FIELD_QUOTE_REQUEST_VERSION_REFERENCE);

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
