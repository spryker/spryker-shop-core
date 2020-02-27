<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Handler;

use DateTime;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderListFormatTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use SprykerShop\Yves\CustomerPage\Form\OrderSearchForm;
use Symfony\Component\Form\FormInterface;

class OrderSearchFormHandler implements OrderSearchFormHandlerInterface
{
    protected const DATE_FORMAT = 'Y-m-d H:i:s';

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
        if (!$orderSearchForm->isSubmitted() || !$orderSearchForm->isValid()) {
            return $orderListTransfer;
        }

        $orderSearchFormData = $orderSearchForm->getData();

        $orderListTransfer = $this->handleSearchGroupInputs($orderSearchFormData, $orderListTransfer);
        $orderListTransfer = $this->handleDateInputs($orderSearchFormData, $orderListTransfer);
        $orderListTransfer = $this->handleOrderInputs($orderSearchFormData, $orderListTransfer);
        $orderListTransfer = $this->handleIsOrderItemsVisibleInput($orderSearchFormData, $orderListTransfer);

        return $orderListTransfer;
    }

    /**
     * @param array $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function handleSearchGroupInputs(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $searchGroup = $orderSearchFormData[OrderSearchForm::FIELD_SEARCH_GROUP] ?? null;
        $searchText = $orderSearchFormData[OrderSearchForm::FIELD_SEARCH_TEXT] ?? null;

        if ($searchGroup && $searchText) {
            $orderListTransfer->addFilterField(
                $this->createFilterFieldTransfer($searchGroup, trim($searchText))
            );
        }

        return $orderListTransfer;
    }

    /**
     * @param array $orderSearchFormData
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function handleDateInputs(
        array $orderSearchFormData,
        OrderListTransfer $orderListTransfer
    ): OrderListTransfer {
        $dateFrom = $orderSearchFormData[OrderSearchForm::FIELD_DATE_FROM] ?? null;
        $dateTo = $orderSearchFormData[OrderSearchForm::FIELD_DATE_TO] ?? null;

        if ($dateFrom instanceof DateTime) {
            $orderListTransfer->addFilterField(
                $this->createFilterFieldTransfer(
                    OrderSearchForm::FIELD_DATE_FROM,
                    $dateFrom->format(static::DATE_FORMAT)
                )
            );
        }

        if ($dateTo instanceof DateTime) {
            $orderListTransfer->addFilterField(
                $this->createFilterFieldTransfer(
                    OrderSearchForm::FIELD_DATE_TO,
                    $dateTo->format(static::DATE_FORMAT)
                )
            );
        }

        return $orderListTransfer;
    }

    /**
     * @param array $orderSearchFormData
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

        if ($orderBy && $orderDirection) {
            $filterTransfer = (new FilterTransfer())
                ->setOrderBy($orderBy)
                ->setOrderDirection($orderDirection);

            $orderListTransfer->setFilter($filterTransfer);
        }

        return $orderListTransfer;
    }

    /**
     * @param array $orderSearchFormData
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
            isset($orderSearchFormData[OrderSearchForm::FIELD_IS_ORDER_ITEMS_VISIBLE])
        );

        return $orderListTransfer->setFormat($orderListFormatTransfer);
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
