<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\FormHandler;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;

class OrderSearchFormHandler implements OrderSearchFormHandlerInterface
{
    /**
     * @uses \Spryker\Zed\CompanySalesConnector\CompanySalesConnectorConfig::FILTER_FIELD_TYPE_COMPANY
     */
    public const CHOICE_COMPANY = 'company';
    public const CHOICE_CUSTOMER = 'customer';

    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::FILTER_FIELD_TYPE_CUSTOMER_REFERENCE
     */
    protected const FILTER_FIELD_TYPE_CUSTOMER_REFERENCE = 'customerReference';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\OrderSearchForm::FIELD_FILTERS
     */
    protected const FIELD_FILTERS = 'filters';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorConfig::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT
     */
    protected const FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT = 'companyBusinessUnit';

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface $customerClient
     */
    public function __construct(CompanyPageToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param array $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function handleOrderSearchFormSubmit(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $companyBusinessUnitValue = $orderSearchFormData[static::FIELD_FILTERS][static::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT] ?? null;

        if (!$companyBusinessUnitValue || $companyBusinessUnitValue === static::CHOICE_CUSTOMER) {
            return $orderListTransfer;
        }

        $orderListTransfer = $this->excludeCustomerReferenceFilterField($orderListTransfer);

        if ($companyBusinessUnitValue === static::CHOICE_COMPANY) {
            return $this->addCompanyFilterField($orderListTransfer);
        }

        return $this->addCompanyBusinessUnitFilterField($companyBusinessUnitValue, $orderListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function excludeCustomerReferenceFilterField(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $filterFieldTransfers = new ArrayObject();

        foreach ($orderListTransfer->getFilterFields() as $filterFieldTransfer) {
            if ($filterFieldTransfer->getType() !== static::FILTER_FIELD_TYPE_CUSTOMER_REFERENCE) {
                $filterFieldTransfers->append($filterFieldTransfer);
            }
        }

        return $orderListTransfer->setFilterFields($filterFieldTransfers);
    }

    /**
     * @param string $companyBusinessUnitValue
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function addCompanyBusinessUnitFilterField(
        string $companyBusinessUnitValue,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setValue($companyBusinessUnitValue)
            ->setType(static::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT);

        return $orderListTransfer->addFilterField($filterFieldTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function addCompanyFilterField(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if (!$customerTransfer) {
            return $orderListTransfer;
        }

        $companyUuid = $this->extractCompanyUuid($customerTransfer);

        if (!$companyUuid) {
            return $orderListTransfer;
        }

        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setValue($companyUuid)
            ->setType(static::CHOICE_COMPANY);

        return $orderListTransfer->addFilterField($filterFieldTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string|null
     */
    protected function extractCompanyUuid(CustomerTransfer $customerTransfer): ?string
    {
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        if (!$companyUserTransfer || !$companyUserTransfer->getCompanyBusinessUnit()) {
            return null;
        }

        $companyBusinessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();

        if (!$companyBusinessUnitTransfer->getCompany()) {
            return null;
        }

        return $companyBusinessUnitTransfer->getCompany()->getUuid();
    }
}
