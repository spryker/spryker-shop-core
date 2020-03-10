<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget\FormExpander;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientInterface;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderSearchFormExpander implements OrderSearchFormExpanderInterface
{
    protected const FIELD_BUSINESS_UNIT = 'businessUnit';

    /**
     * @var \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientInterface
     */
    protected $companyBusinessUnitSalesConnectorClient;

    /**
     * @var \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientInterface $companyBusinessUnitSalesConnectorClient
     * @param \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface $customerClient
     */
    public function __construct(
        CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientInterface $companyBusinessUnitSalesConnectorClient,
        CompanyBusinessUnitWidgetToCustomerClientInterface $customerClient
    ) {
        $this->companyBusinessUnitSalesConnectorClient = $companyBusinessUnitSalesConnectorClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expandOrderSearchFormWithBusinessUnitField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            static::FIELD_BUSINESS_UNIT,
            ChoiceType::class,
            [
                'choices' => $this->getCompanyBusinessUnitChoices(),
                'required' => false,
                'placeholder' => false,
                'label' => 'company_business_unit_widget.business_unit',
            ]
        );
    }

    /**
     * @return int[]
     */
    protected function getCompanyBusinessUnitChoices(): array
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return [];
        }

        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitSalesConnectorClient->getPermittedCompanyBusinessUnitCollection(
            $customerTransfer->getCompanyUserTransfer()
        );

        return $this->getChoicesFromCompanyBusinessUnitCollection($companyBusinessUnitCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return int[]
     */
    protected function getChoicesFromCompanyBusinessUnitCollection(
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): array {
        $choices = [];

        $choices['company_business_unit_widget.choice.my_orders'] = -1;

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $choices[$companyBusinessUnitTransfer->getName()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        }

        if (count($choices) > 2) {
            $choices['company_business_unit_widget.choice.company_orders'] = 0;
        }

        return $choices;
    }
}
