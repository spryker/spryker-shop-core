<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Handler;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\OrderListFormatTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Form\OrderSearchFiltersForm;
use SprykerShop\Yves\CustomerPage\Form\OrderSearchForm;
use Symfony\Component\Form\FormInterface;

class OrderSearchFormHandler implements OrderSearchFormHandlerInterface
{
    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::FILTER_TYPE_CUSTOMER_REFERENCE
     *
     * @var string
     */
    protected const FILTER_FIELD_TYPE_CUSTOMER_REFERENCE = 'customerReference';

    /**
     * @see \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::ORDER_BY_COLUMN_MAPPING
     *
     * @var string
     */
    protected const FILTER_FIELD_TYPE_DATE = 'date';

    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d H:i';

    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormHandlerPluginInterface>
     */
    protected $orderSearchFormHandlerPlugins;

    /**
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface $customerClient
     * @param array<\SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormHandlerPluginInterface> $orderSearchFormHandlerPlugins
     */
    public function __construct(
        CustomerPageToCustomerClientInterface $customerClient,
        array $orderSearchFormHandlerPlugins
    ) {
        $this->customerClient = $customerClient;
        $this->orderSearchFormHandlerPlugins = $orderSearchFormHandlerPlugins;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $orderSearchForm
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function handleOrderSearchFormSubmit(
        FormInterface $orderSearchForm,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $orderListTransfer = $this->resetFilterFields($orderListTransfer);

        if (!$orderSearchForm->isSubmitted() || !$orderSearchForm->isValid()) {
            return $this->addOrderByFilter($orderListTransfer, static::FILTER_FIELD_TYPE_DATE);
        }

        $orderSearchFormData = $orderSearchForm->getData();

        $orderListTransfer = $this->handleSearchTypeInputs($orderSearchFormData, $orderListTransfer);
        $orderListTransfer = $this->handleDateInputs($orderSearchFormData, $orderListTransfer);
        $orderListTransfer = $this->handleOrderInputs($orderSearchFormData, $orderListTransfer);
        $orderListTransfer = $this->handleIsOrderItemsVisibleInput($orderSearchFormData, $orderListTransfer);

        $orderListTransfer = $this->executeOrderSearchFormHandlerPlugins($orderSearchFormData, $orderListTransfer);

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function resetFilterFields(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer->setFilterFields(new ArrayObject());

        $filterFieldTransfer = $this->createFilterFieldTransfer(
            static::FILTER_FIELD_TYPE_CUSTOMER_REFERENCE,
            $this->customerClient->getCustomer()->getCustomerReference(),
        );

        return $orderListTransfer->addFilterField($filterFieldTransfer);
    }

    /**
     * @param array<string, mixed> $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function handleSearchTypeInputs(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $searchType = $orderSearchFormData[OrderSearchForm::FIELD_SEARCH_TYPE] ?? null;
        $searchText = $orderSearchFormData[OrderSearchForm::FIELD_SEARCH_TEXT] ?? null;

        if ($searchType && $searchText) {
            $orderListTransfer->addFilterField(
                $this->createFilterFieldTransfer($searchType, trim($searchText)),
            );
        }

        return $orderListTransfer;
    }

    /**
     * @param array<string, mixed> $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function handleDateInputs(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $dateFrom = $orderSearchFormData[OrderSearchForm::FIELD_FILTERS][OrderSearchFiltersForm::FIELD_DATE_FROM] ?? null;
        $dateTo = $orderSearchFormData[OrderSearchForm::FIELD_FILTERS][OrderSearchFiltersForm::FIELD_DATE_TO] ?? null;

        if ($dateFrom instanceof DateTime) {
            $orderListTransfer->addFilterField(
                $this->createFilterFieldTransfer(
                    OrderSearchFiltersForm::FIELD_DATE_FROM,
                    $dateFrom->format(static::DATE_FORMAT),
                ),
            );
        }

        if ($dateTo instanceof DateTime) {
            $modifiedDateTo = clone $dateTo;

            $orderListTransfer->addFilterField(
                $this->createFilterFieldTransfer(
                    OrderSearchFiltersForm::FIELD_DATE_TO,
                    $modifiedDateTo->modify('+1 minute')->format(static::DATE_FORMAT),
                ),
            );
        }

        return $orderListTransfer;
    }

    /**
     * @param array<string, mixed> $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function handleOrderInputs(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $orderBy = $orderSearchFormData[OrderSearchForm::FIELD_ORDER_BY] ?? null;
        $orderDirection = $orderSearchFormData[OrderSearchForm::FIELD_ORDER_DIRECTION] ?? null;

        if (!$orderBy) {
            $orderBy = static::FILTER_FIELD_TYPE_DATE;
        }

        return $this->addOrderByFilter($orderListTransfer, $orderBy, $orderDirection);
    }

    /**
     * @param array<string, mixed> $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function handleIsOrderItemsVisibleInput(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $orderListFormatTransfer = new OrderListFormatTransfer();

        $orderListFormatTransfer->setExpandWithItems(
            $orderSearchFormData[OrderSearchForm::FIELD_IS_ORDER_ITEMS_VISIBLE],
        );

        return $orderListTransfer->setFormat($orderListFormatTransfer);
    }

    /**
     * @param array<string, mixed> $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function executeOrderSearchFormHandlerPlugins(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        foreach ($this->orderSearchFormHandlerPlugins as $orderSearchFormHandlerPlugin) {
            $orderListTransfer = $orderSearchFormHandlerPlugin->handle($orderSearchFormData, $orderListTransfer);
        }

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param string $orderBy
     * @param string|null $orderDirection
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function addOrderByFilter(
        OrderListTransfer $orderListTransfer,
        string $orderBy,
        ?string $orderDirection = null
    ): OrderListTransfer {
        if (!$orderDirection) {
            $orderDirection = 'DESC';
        }

        /**
         * @see \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::addOrderByFilter()
         */
        $orderByFilterValue = sprintf('%s::%s', $orderBy, $orderDirection);

        return $orderListTransfer->addFilterField(
            $this->createFilterFieldTransfer(OrderSearchForm::FIELD_ORDER_BY, $orderByFilterValue),
        );
    }

    /**
     * @param string $type
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\FilterFieldTransfer
     */
    protected function createFilterFieldTransfer(string $type, string $value): FilterFieldTransfer
    {
        return (new FilterFieldTransfer())
            ->setType($type)
            ->setValue($value);
    }
}
