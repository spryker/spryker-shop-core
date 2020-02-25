<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Handler;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\FilterFieldTransfer;
use SprykerShop\Yves\CustomerPage\Form\OrderSearchForm;
use Symfony\Component\Form\FormInterface;

class OrderSearchFormHandler implements OrderSearchFormHandlerInterface
{
    protected const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param \Symfony\Component\Form\FormInterface $orderSearchForm
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FilterFieldTransfer[]
     */
    public function handleOrderSearchFormSubmit(FormInterface $orderSearchForm): ArrayObject
    {
        $filterFieldTransfers = new ArrayObject();

        if (!$orderSearchForm->isSubmitted() || !$orderSearchForm->isValid()) {
            return $filterFieldTransfers;
        }

        $orderSearchFormData = $orderSearchForm->getData();

        $filterFieldTransfers = $this->handleSearchGroupInputs($orderSearchFormData, $filterFieldTransfers);
        $filterFieldTransfers = $this->handleDateInputs($orderSearchFormData, $filterFieldTransfers);

        return $filterFieldTransfers;
    }

    /**
     * @param array $orderSearchFormData
     * @param \ArrayObject $filterFieldTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FilterFieldTransfer[]
     */
    protected function handleSearchGroupInputs(array $orderSearchFormData, ArrayObject $filterFieldTransfers): ArrayObject
    {
        $searchGroup = $orderSearchFormData[OrderSearchForm::FIELD_SEARCH_GROUP] ?? null;
        $searchText = $orderSearchFormData[OrderSearchForm::FIELD_SEARCH_TEXT] ?? null;

        if ($searchGroup && $searchText) {
            $filterFieldTransfer = (new FilterFieldTransfer())
                ->setType($searchGroup)
                ->setValue(trim($searchText));

            $filterFieldTransfers->append($filterFieldTransfer);
        }

        return $filterFieldTransfers;
    }

    /**
     * @param array $orderSearchFormData
     * @param \ArrayObject $filterFieldTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FilterFieldTransfer[]
     */
    protected function handleDateInputs(array $orderSearchFormData, ArrayObject $filterFieldTransfers): ArrayObject
    {
        $dateFrom = $orderSearchFormData[OrderSearchForm::FIELD_DATE_FROM] ?? null;
        $dateTo = $orderSearchFormData[OrderSearchForm::FIELD_DATE_TO] ?? null;

        if ($dateFrom instanceof DateTime) {
            $filterFieldTransfer = (new FilterFieldTransfer())
                ->setType(OrderSearchForm::FIELD_DATE_FROM)
                ->setValue($dateFrom->format(static::DATE_FORMAT));

            $filterFieldTransfers->append($filterFieldTransfer);
        }

        if ($dateTo instanceof DateTime) {
            $filterFieldTransfer = (new FilterFieldTransfer())
                ->setType(OrderSearchForm::FIELD_DATE_TO)
                ->setValue($dateTo->format(static::DATE_FORMAT));

            $filterFieldTransfers->append($filterFieldTransfer);
        }

        return $filterFieldTransfers;
    }
}
