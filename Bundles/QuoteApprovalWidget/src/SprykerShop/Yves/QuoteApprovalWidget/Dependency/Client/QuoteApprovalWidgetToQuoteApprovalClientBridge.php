<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteApprovalWidgetToQuoteApprovalClientBridge implements QuoteApprovalWidgetToQuoteApprovalClientInterface
{
    /**
     * @var \Spryker\Client\QuoteApproval\QuoteApprovalClientInterface
     */
    protected $quoteApprovalClient;

    /**
     * @param \Spryker\Client\QuoteApproval\QuoteApprovalClientInterface $quoteApprovalClient
     */
    public function __construct($quoteApprovalClient)
    {
        $this->quoteApprovalClient = $quoteApprovalClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkCheckoutStepAccessibility(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteApprovalClient->checkCheckoutStepAccessibility($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function canQuoteBeApprovedByCurrentCustomer(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteApprovalClient->canQuoteBeApprovedByCurrentCustomer($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function isCompanyUserInQuoteApproverList(QuoteTransfer $quoteTransfer, int $idCompanyUser): bool
    {
        return $this->quoteApprovalClient->isCompanyUserInQuoteApproverList($quoteTransfer, $idCompanyUser);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    public function findWaitingQuoteApprovalByIdCompanyUser(QuoteTransfer $quoteTransfer, int $idCompanyUser): ?QuoteApprovalTransfer
    {
        return $this->quoteApprovalClient->findWaitingQuoteApprovalByIdCompanyUser($quoteTransfer, $idCompanyUser);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->quoteApprovalClient->createQuoteApproval($quoteApprovalRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->quoteApprovalClient->removeQuoteApproval($quoteApprovalRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getQuoteApproverList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        return $this->quoteApprovalClient->getQuoteApproverList($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return int|null
     */
    public function calculateApproveQuotePermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int
    {
        return $this->quoteApprovalClient->calculateApproveQuotePermissionLimit($quoteTransfer, $companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return int|null
     */
    public function calculatePlaceOrderPermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int
    {
        return $this->quoteApprovalClient->calculatePlaceOrderPermissionLimit($quoteTransfer, $companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteWaitingForApproval(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteApprovalClient->isQuoteWaitingForApproval($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApproved(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteApprovalClient->isQuoteApproved($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function calculateQuoteStatus(QuoteTransfer $quoteTransfer): ?string
    {
        return $this->quoteApprovalClient->calculateQuoteStatus($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->quoteApprovalClient->approveQuoteApproval($quoteApprovalRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function declineQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->quoteApprovalClient->declineQuoteApproval($quoteApprovalRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApplicableForApprovalProcess(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteApprovalClient->isQuoteApplicableForApprovalProcess($quoteTransfer);
    }
}
