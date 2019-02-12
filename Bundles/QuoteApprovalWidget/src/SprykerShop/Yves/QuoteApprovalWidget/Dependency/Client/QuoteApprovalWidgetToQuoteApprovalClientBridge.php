<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer;
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
    public function isQuoteCanBeApprovedByCurrentCustomer(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteApprovalClient->isQuoteCanBeApprovedByCurrentCustomer($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function hasQuoteApprovalsForCompanyUser(QuoteTransfer $quoteTransfer, int $idCompanyUser): bool
    {
        return $this->quoteApprovalClient->hasQuoteApprovalsForCompanyUser($quoteTransfer, $idCompanyUser);
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
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->quoteApprovalClient->createQuoteApproval($quoteApprovalCreateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->quoteApprovalClient->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getQuoteApproversList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        return $this->quoteApprovalClient->getQuoteApproversList($quoteTransfer);
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
     * @return bool
     */
    public function isCustomerHasPlaceOrderPermission(): bool
    {
        return $this->quoteApprovalClient->isCustomerHasPlaceOrderPermission();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteRequireApproval(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteApprovalClient->isQuoteRequireApproval($quoteTransfer);
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
}
