<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Form\CompanyBusinessUnitForm;

class CompanyBusinessUnitFormDataProvider
{
    use PermissionAwareTrait;

    protected const CHOICE_CUSTOMER = 'customer';
    protected const CHOICE_COMPANY = 'company';

    protected const GLOSSARY_KEY_CHOICE_MY_ORDERS = 'company_business_unit_widget.choice.my_orders';
    protected const GLOSSARY_KEY_CHOICE_COMPANY_ORDERS = 'company_business_unit_widget.choice.company_orders';

    /**
     * @var \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @param \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     */
    public function __construct(
        CompanyBusinessUnitWidgetToCustomerClientInterface $customerClient,
        CompanyBusinessUnitWidgetToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
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
            CompanyBusinessUnitForm::OPTION_COMPANY_BUSINESS_UNIT_CHOICES => $this->getCompanyBusinessUnitChoices(),
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
        $choices = [static::GLOSSARY_KEY_CHOICE_MY_ORDERS => static::CHOICE_CUSTOMER];

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $choices[$companyBusinessUnitTransfer->getName()] = $companyBusinessUnitTransfer->getUuid();
        }

        if (count($choices) > 2) {
            $choices[static::GLOSSARY_KEY_CHOICE_COMPANY_ORDERS] = static::CHOICE_COMPANY;
        }

        return $choices;
    }
}
