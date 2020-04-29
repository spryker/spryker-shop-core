<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Form\Handler;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToCustomerClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToStoreClientInterface;
use SprykerShop\Yves\SalesReturnPage\Form\DataProvider\ReturnCreateFormDataProvider;
use SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm;

class ReturnHandler implements ReturnHandlerInterface
{
    protected const GLOSSARY_KEY_CREATE_RETURN_SELECTED_ITEMS_ERROR = 'return.create_return.validation.selected_items';

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Form\ReturnItemsForm::FIELD_CUSTOM_REASON
     */
    protected const FIELD_CUSTOM_REASON = 'customReason';

    /**
     * @var \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @var \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface $salesReturnClient
     * @param \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToStoreClientInterface $storeClient
     */
    public function __construct(
        SalesReturnPageToSalesReturnClientInterface $salesReturnClient,
        SalesReturnPageToCustomerClientInterface $customerClient,
        SalesReturnPageToStoreClientInterface $storeClient
    ) {
        $this->salesReturnClient = $salesReturnClient;
        $this->customerClient = $customerClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param array $returnItemsList
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(array $returnItemsList): ReturnResponseTransfer
    {
        $returnItemData = isset($returnItemsList[ReturnCreateForm::FIELD_RETURN_ITEMS])
            ? $returnItemsList[ReturnCreateForm::FIELD_RETURN_ITEMS]
            : [];

        $returnCreateRequestTransfer = $this->createReturnCreateRequestTransfer($returnItemData);

        if ($returnCreateRequestTransfer->getReturnItems()->count()) {
            return $this->salesReturnClient->createReturn($returnCreateRequestTransfer);
        }

        return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_SELECTED_ITEMS_ERROR);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function createErrorReturnResponse(string $message): ReturnResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }

    /**
     * @param array $returnItemData
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function createReturnCreateRequestTransfer(array $returnItemData): ReturnCreateRequestTransfer
    {
        $returnItemTransfersCollection = new ArrayObject();
        $returnCreateRequestTransfer = (new ReturnCreateRequestTransfer())
            ->setCustomer($this->customerClient->getCustomer())
            ->setStore($this->storeClient->getCurrentStore()->getName());

        foreach ($returnItemData as $returnItem) {
            if (!$returnItem[ReturnItemTransfer::UUID]) {
                continue;
            }

            $returnItemTransfer = (new ReturnItemTransfer())->fromArray($returnItem, true);

            if ($returnItem[ReturnItemTransfer::REASON] === ReturnCreateFormDataProvider::CUSTOM_REASON_VALUE && $returnItem[static::FIELD_CUSTOM_REASON]) {
                $returnItemTransfer->setReason($returnItem[static::FIELD_CUSTOM_REASON]);
            }

            $returnItemTransfersCollection->append($returnItemTransfer);
        }

        $returnCreateRequestTransfer->setReturnItems($returnItemTransfersCollection);

        return $returnCreateRequestTransfer;
    }
}
