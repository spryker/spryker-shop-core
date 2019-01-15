<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Form;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface;

class QuoteApproveRequestFormDataProvider implements QuoteApproveRequestFormDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface
     */
    protected $quoteApprovalClient;

    /**
     * @var \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface $quoteApprovalClient
     * @param \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        QuoteApprovalWidgetToQuoteApprovalClientInterface $quoteApprovalClient,
        QuoteApprovalWidgetToCustomerClientInterface $customerClient,
        QuoteApprovalWidgetToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->quoteApprovalClient = $quoteApprovalClient;
        $this->customerClient = $customerClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return array
     */
    public function getOptions(QuoteTransfer $quoteTransfer, string $localeName): array
    {
        return [
            'data_class' => QuoteApproveRequestTransfer::class,
            QuoteApproveRequestForm::OPTION_APPROVERS_LIST => $this->getPotentialApproversList($quoteTransfer, $localeName),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApproveRequestTransfer
     */
    public function getData(QuoteTransfer $quoteTransfer): QuoteApproveRequestTransfer
    {
        return (new QuoteApproveRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setCustomer($this->customerClient->getCustomer());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return array
     */
    protected function getPotentialApproversList(QuoteTransfer $quoteTransfer, string $localeName): array
    {
        $potentialApproversCollection = $this->quoteApprovalClient
            ->getPotentialQuoteApproversList($quoteTransfer);

        $potentialApproversList = [];

        foreach ($potentialApproversCollection->getCompanyUsers() as $approver) {
            $label = $this->getChoiceLabel($quoteTransfer, $approver, $localeName);

            $potentialApproversList[$label] = $approver->getIdCompanyUser();
        }

        return $potentialApproversList;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param string $localeName
     *
     * @return string
     */
    protected function getChoiceLabel(
        QuoteTransfer $quoteTransfer,
        CompanyUserTransfer $companyUserTransfer,
        string $localeName
    ): string {
        $customerTransfer = $companyUserTransfer->getCustomer();

        return sprintf(
            '%s %s (%s)',
            $customerTransfer->getFirstName(),
            $customerTransfer->getLastName(),
            $this->getApproverLimitString(
                $companyUserTransfer,
                $quoteTransfer,
                $localeName
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return string
     */
    protected function getApproverLimitString(
        CompanyUserTransfer $companyUserTransfer,
        QuoteTransfer $quoteTransfer,
        string $localeName
    ) {
        $approverLimit = $this->findApproverLimit($companyUserTransfer, $quoteTransfer);

        if ($approverLimit === null) {
            return $this->glossaryStorageClient->translate(
                'quote_approval_widget.limit.unlimited',
                $localeName
            );
        }

        return ($approverLimit / 100) . $quoteTransfer->getCurrency()->getSymbol();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function findApproverLimit(
        CompanyUserTransfer $companyUserTransfer,
        QuoteTransfer $quoteTransfer
    ): ?int {
        $highestApproverPermissionLimit = null;

        foreach ($companyUserTransfer->getCompanyRoleCollection()->getRoles() as $companyRoleTransfer) {
            $quoteApprovePermissionTransfer = $this->findPermissionByKey($companyRoleTransfer->getPermissionCollection());

            if ($quoteApprovePermissionTransfer === null) {
                continue;
            }

            $approverPermissionLimit = $this->findApproverLimitInPermissionConfiguration(
                $quoteApprovePermissionTransfer,
                $quoteTransfer
            );

            if ($approverPermissionLimit > $highestApproverPermissionLimit) {
                $highestApproverPermissionLimit = $approverPermissionLimit;
            }
        }

        return $highestApproverPermissionLimit;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $quoteApprovePermission
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function findApproverLimitInPermissionConfiguration(
        PermissionTransfer $quoteApprovePermission,
        QuoteTransfer $quoteTransfer
    ): ?int {
        $configuration = $quoteApprovePermission->getConfiguration();
        $currencyCode = $quoteTransfer->getCurrency()->getCode();
        $storeName = $quoteTransfer->getStore()->getName();

        return $configuration[ApproveQuotePermissionPlugin::FIELD_STORE_MULTI_CURRENCY][$storeName][$currencyCode] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer|null
     */
    protected function findPermissionByKey(
        PermissionCollectionTransfer $permissionCollectionTransfer
    ): ?PermissionTransfer {
        foreach ($permissionCollectionTransfer->getPermissions() as $permission) {
            if ($permission->getKey() === ApproveQuotePermissionPlugin::KEY) {
                return $permission;
            }
        }

        return null;
    }
}
