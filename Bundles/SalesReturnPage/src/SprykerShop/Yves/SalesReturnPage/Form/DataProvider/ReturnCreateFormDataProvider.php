<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface;
use SprykerShop\Yves\SalesReturnPage\Form\ReturnItemsForm;

class ReturnCreateFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @param \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface $salesReturnClient
     */
    public function __construct(
        SalesReturnPageToSalesReturnClientInterface $salesReturnClient
    ) {
        $this->salesReturnClient = $salesReturnClient;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function getData(OrderTransfer $orderTransfer): ReturnCreateRequestTransfer
    {
        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($orderTransfer->getCustomer());

        return $this->expandReturnCreateransferWithReturnItemTransfer($returnCreateRequestTransfer, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function expandReturnCreateransferWithReturnItemTransfer(
        ReturnCreateRequestTransfer $returnCreateRequestTransfer,
        OrderTransfer $orderTransfer
    ): ReturnCreateRequestTransfer {
        if (!$orderTransfer->getItems()->count()) {
            return $returnCreateRequestTransfer;
        }

        $returnItemTransfersCollection = new ArrayObject();

        foreach ($orderTransfer->getItems() as $orderItemTransfer) {
            $returnItemTransfer = (new ReturnItemTransfer())
                ->setOrderItem($orderItemTransfer)
                ->setUuid($orderItemTransfer->getUuid());

            $returnItemTransfersCollection->append($returnItemTransfer);
        }

        return $returnCreateRequestTransfer->setReturnItems($returnItemTransfersCollection);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ReturnItemsForm::OPTION_REUTN_REASONS => $this->prepareReturnReasonChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function prepareReturnReasonChoices(): array
    {
        $result = [];
        $returnReasonCollectionTransfer = $this->salesReturnClient->getReturnReasons((new ReturnReasonFilterTransfer()));

        foreach ($returnReasonCollectionTransfer->getReturnReasons() as $returnReasonTransfer) {
            $result[$returnReasonTransfer->getGlossaryKeyReason()] = $returnReasonTransfer->getGlossaryKeyReason();
        }

        $result['custom_return_reason'] = 'custom_return_reason';

        return $result;
    }
}
