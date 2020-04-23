<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Form\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToCustomerClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToStoreClientInterface;
use SprykerShop\Yves\SalesReturnPage\Form\DataProvider\ReturnCreateFormDataProvider;
use SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm;
use SprykerShop\Yves\SalesReturnPage\Form\ReturnItemsForm;

class ReturnHandler implements ReturnHandlerInterface
{
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
    public function createReturnResponseTransfer(array $returnItemsList): ReturnResponseTransfer
    {
        $returnItemData = isset($returnItemsList[ReturnCreateForm::FIELD_RETURN_ITEMS])
            ? $returnItemsList[ReturnCreateForm::FIELD_RETURN_ITEMS]
            : [];

        $returnCreateRequestTransfer = $this->createReturnCreateRequestTransfer($returnItemData);
        $returnResponseTransfer = $this->salesReturnClient->createReturn($returnCreateRequestTransfer);

        return $returnResponseTransfer;
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
            if (!$returnItem[ReturnItemsForm::FIELD_UUID]) {
                continue;
            }

            $returnItemTransfer = (new ReturnItemTransfer())->fromArray($returnItem, true);

            if ($returnItem[ReturnItemsForm::FIELD_REASON] === ReturnCreateFormDataProvider::GLOSSARY_KEY_CUSTOM_REASON && $returnItem[ReturnItemsForm::FIELD_CUSTOM_REASON]) {
                $returnItemTransfer->setReason($returnItem[ReturnItemsForm::FIELD_CUSTOM_REASON]);
            }

            $returnItemTransfersCollection->append($returnItemTransfer);
        }

        $returnCreateRequestTransfer->setReturnItems($returnItemTransfersCollection);

        return $returnCreateRequestTransfer;
    }
}
