<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Form;

use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @return array
     */
    protected function getPotentialApproversList(QuoteTransfer $quoteTransfer): array
    {
        $potentialApproversCollection = $this->quoteApprovalClient
            ->getPotentialQuoteApproversList($quoteTransfer);

        $potentialApproversList = [];

        foreach ($potentialApproversCollection->getCompanyUsers() as $approver) {
            $label = sprintf(
                '%s %s',
                $approver->getCustomer()->getFirstName(),
                $approver->getCustomer()->getLastName()
            );

            $potentialApproversList[$label] = $approver->getIdCompanyUser();
        }

        return $potentialApproversList;
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
}
