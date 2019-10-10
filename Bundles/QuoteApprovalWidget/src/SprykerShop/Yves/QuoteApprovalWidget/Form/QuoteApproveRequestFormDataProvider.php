<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Form;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToMoneyClientInterface;
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
     * @var \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToMoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface $quoteApprovalClient
     * @param \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToMoneyClientInterface $moneyClient
     */
    public function __construct(
        QuoteApprovalWidgetToQuoteApprovalClientInterface $quoteApprovalClient,
        QuoteApprovalWidgetToCustomerClientInterface $customerClient,
        QuoteApprovalWidgetToGlossaryStorageClientInterface $glossaryStorageClient,
        QuoteApprovalWidgetToMoneyClientInterface $moneyClient
    ) {
        $this->quoteApprovalClient = $quoteApprovalClient;
        $this->customerClient = $customerClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->moneyClient = $moneyClient;
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
            'data_class' => QuoteApprovalRequestTransfer::class,
            QuoteApproveRequestForm::OPTION_APPROVERS_LIST => $this->getApproversList($quoteTransfer, $localeName),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalRequestTransfer
     */
    public function getData(QuoteTransfer $quoteTransfer): QuoteApprovalRequestTransfer
    {
        $quoteApprovalRequestTransfer = new QuoteApprovalRequestTransfer();

        $customerTransfer = $this->customerClient->getCustomer();

        if (!$customerTransfer) {
            return $quoteApprovalRequestTransfer;
        }

        $customerTransfer->requireCompanyUserTransfer();

        return $quoteApprovalRequestTransfer
            ->setRequesterCompanyUserId($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return array
     */
    protected function getApproversList(QuoteTransfer $quoteTransfer, string $localeName): array
    {
        $quoteApproverCollection = $this->quoteApprovalClient
            ->getQuoteApproverList($quoteTransfer);

        $quoteApproverList = [];

        foreach ($quoteApproverCollection->getCompanyUsers() as $approver) {
            $label = $this->getChoiceLabel($quoteTransfer, $approver, $localeName);

            $quoteApproverList[$label] = $approver->getIdCompanyUser();
        }

        return $quoteApproverList;
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
        $approverLimit = $this->quoteApprovalClient->calculateApproveQuotePermissionLimit(
            $quoteTransfer,
            $companyUserTransfer
        );

        if ($approverLimit === null) {
            return $this->glossaryStorageClient->translate(
                'quote_approval_widget.limit.unlimited',
                $localeName
            );
        }

        return $this->moneyClient->formatWithSymbol(
            $this->moneyClient->fromInteger($approverLimit, $quoteTransfer->getCurrency()->getCode())
        );
    }
}
