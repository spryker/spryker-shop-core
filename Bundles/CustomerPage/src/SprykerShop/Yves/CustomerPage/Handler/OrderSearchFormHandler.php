<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\FilterFieldTransfer;
use SprykerShop\Yves\CustomerPage\Form\OrderSearchForm;
use Symfony\Component\Form\FormInterface;

class OrderSearchFormHandler implements OrderSearchFormHandlerInterface
{
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

        $filterFieldTransfers = $this->handleSearchGroup($orderSearchFormData, $filterFieldTransfers);

        return $filterFieldTransfers;
    }

    /**
     * @param array $orderSearchFormData
     * @param \ArrayObject $filterFieldTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FilterFieldTransfer[]
     */
    protected function handleSearchGroup(array $orderSearchFormData, ArrayObject $filterFieldTransfers): ArrayObject
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
}
