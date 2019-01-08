<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Form;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface;
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
     * @param \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface $quoteApprovalClient
     * @param \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface $customerClient
     */
    public function __construct(
        QuoteApprovalWidgetToQuoteApprovalClientInterface $quoteApprovalClient,
        QuoteApprovalWidgetToCustomerClientInterface $customerClient
    ) {
        $this->quoteApprovalClient = $quoteApprovalClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @return array
     */
    public function getOptions(QuoteTransfer $quoteTransfer): array
    {
        return [
            'data_class' => QuoteApproveRequestTransfer::class,
            QuoteApproveRequestForm::OPTION_APPROVERS_LIST => $this->getPotentialApproversList($quoteTransfer),
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
     *
     * @return array
     */
    protected function getPotentialApproversList(QuoteTransfer $quoteTransfer): array
    {
        $potentialApproversCollection = $this->quoteApprovalClient
            ->getPotentialQuoteApproversList($quoteTransfer);

        $potentialApproversList = [];

        foreach ($potentialApproversCollection->getCompanyUsers() as $approver) {
            $label = $this->getChoiceLabel($quoteTransfer, $approver);

            $potentialApproversList[$label] = $approver->getIdCompanyUser();
        }

        return $potentialApproversList;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $approver
     *
     * @return string
     */
    protected function getChoiceLabel(QuoteTransfer $quoteTransfer, CompanyUserTransfer $approver): string
    {
        $customer = $approver->getCustomer();
        $currency = $quoteTransfer->getCurrency();

        return sprintf(
            '%s %s (%s%s)',
            $customer->getFirstName(),
            $customer->getLastName(),
            $this->getApproverLimitInCents($approver, $currency->getCode()) / 100,
            $currency->getSymbol()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUser
     * @param string $currencyCode
     *
     * @return int
     */
    protected function getApproverLimitInCents(CompanyUserTransfer $companyUser, string $currencyCode): int
    {
        $highestApproverPermissionLimitInCents = 0;

        foreach ($companyUser->getCompanyRoleCollection()->getRoles() as $companyRole) {
            $quoteApprovePermission = $this->findPermissionByKey($companyRole->getPermissionCollection());

            if ($quoteApprovePermission === null) {
                continue;
            }

            $approverPermissionLimitInCents = $this->getApproverLimitConfiguration(
                $quoteApprovePermission,
                $currencyCode
            );

            if ($approverPermissionLimitInCents > $highestApproverPermissionLimitInCents) {
                $highestApproverPermissionLimitInCents = $approverPermissionLimitInCents;
            }
        }

        return $highestApproverPermissionLimitInCents;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $quoteApprovePermission
     *
     * @return mixed
     */
    protected function getApproverLimitConfiguration(
        PermissionTransfer $quoteApprovePermission,
        string $currencyCode
    ): int {
        $configuration = $quoteApprovePermission->getConfiguration();

        return $configuration[ApproveQuotePermissionPlugin::FIELD_MULTI_CURRENCY][$currencyCode] ?? 0;
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
    }
}
