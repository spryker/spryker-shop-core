<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyPage\Expander\CompanyBusinessUnitOrderSearchFormExpander;
use SprykerShop\Yves\CompanyPage\FormHandler\OrderSearchFormHandler;

class CompanyBusinessUnitOrderSearchFormDataProvider
{
    use PermissionAwareTrait;

    protected const GLOSSARY_KEY_CHOICE_COMPANY_ORDERS = 'company_business_unit_widget.choice.company_orders';

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     */
    public function __construct(
        CompanyPageToCustomerClientInterface $customerClient,
        CompanyPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
    ) {
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            CompanyBusinessUnitOrderSearchFormExpander::OPTION_COMPANY_BUSINESS_UNIT_CHOICES => $this->getCompanyBusinessUnitChoices(),
        ];
    }

    /**
     * @return string[]
     */
    protected function getCompanyBusinessUnitChoices(): array
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return [];
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        $idCompanyUser = $companyUserTransfer->getIdCompanyUser();

        if ($this->can('SeeCompanyOrdersPermissionPlugin', $idCompanyUser)) {
            $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
                ->setIdCompany($companyUserTransfer->getFkCompany());

            return $this->getChoicesFromCompanyBusinessUnitCollection(
                $this->companyBusinessUnitClient->getRawCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer)
            );
        }

        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();

        if ($companyUserTransfer->getCompanyBusinessUnit() && $this->can('SeeBusinessUnitOrdersPermissionPlugin', $idCompanyUser)) {
            $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit(
                $companyUserTransfer->getCompanyBusinessUnit()
            );
        }

        return $this->getChoicesFromCompanyBusinessUnitCollection($companyBusinessUnitCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return string[]
     */
    protected function getChoicesFromCompanyBusinessUnitCollection(
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): array {
        $choices = [];

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $choices[$companyBusinessUnitTransfer->getName()] = $companyBusinessUnitTransfer->getUuid();
        }

        if (count($choices) > 1) {
            $choices[static::GLOSSARY_KEY_CHOICE_COMPANY_ORDERS] = OrderSearchFormHandler::CHOICE_COMPANY;
        }

        return $choices;
    }
}
