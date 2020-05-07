<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Form\DataProvider;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface;
use SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm;
use SprykerShop\Yves\SalesReturnPage\Form\ReturnItemsForm;

class ReturnCreateFormDataProvider
{
    public const GLOSSARY_KEY_CUSTOM_REASON = 'return.return_reasons.custom_reason.name';
    public const CUSTOM_REASON_VALUE = 'custom_reason';

    /**
     * @var \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @var \SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin\SalesReturnPageFormExpanderPluginInterface[]
     */
    protected $salesReturnPageFormExpanderPlugins;

    /**
     * @param \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface $salesReturnClient
     * @param \SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin\SalesReturnPageFormExpanderPluginInterface[] $salesReturnPageFormExpanderPlugins
     */
    public function __construct(SalesReturnPageToSalesReturnClientInterface $salesReturnClient, array $salesReturnPageFormExpanderPlugins)
    {
        $this->salesReturnClient = $salesReturnClient;
        $this->salesReturnPageFormExpanderPlugins = $salesReturnPageFormExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function getData(OrderTransfer $orderTransfer): array
    {
        $returnCreateFormData = [
            ReturnCreateForm::FIELD_RETURN_ITEMS => $this->createReturnItemTransfersCollection($orderTransfer),
        ];

        return $this->executeSalesReturnPageFormFormPlugins($returnCreateFormData);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ReturnItemsForm::OPTION_RETURN_REASONS => $this->prepareReturnReasonChoices(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function createReturnItemTransfersCollection(OrderTransfer $orderTransfer): array
    {
        $returnItemsList = [];

        if (!$orderTransfer->getItems()->count()) {
            return $returnItemsList;
        }

        foreach ($orderTransfer->getItems() as $orderItemTransfer) {
            $returnItemsList[] = [ReturnItemTransfer::ORDER_ITEM => $orderItemTransfer];
        }

        return $returnItemsList;
    }

    /**
     * @return array
     */
    protected function prepareReturnReasonChoices(): array
    {
        $returnReasonChoices = [];
        $returnReasonCollectionTransfer = $this->salesReturnClient->getReturnReasons((new ReturnReasonFilterTransfer()));

        foreach ($returnReasonCollectionTransfer->getReturnReasons() as $returnReasonTransfer) {
            $returnReasonChoices[$returnReasonTransfer->getGlossaryKeyReason()] = $returnReasonTransfer->getGlossaryKeyReason();
        }

        $returnReasonChoices[static::GLOSSARY_KEY_CUSTOM_REASON] = static::CUSTOM_REASON_VALUE;

        return $returnReasonChoices;
    }

    /**
     * @param array $returnCreateFormData
     *
     * @return array
     */
    protected function executeSalesReturnPageFormFormPlugins(array $returnCreateFormData): array
    {
        foreach ($this->salesReturnPageFormExpanderPlugins as $formPlugin) {
            $returnCreateFormData = $formPlugin->expandFormData($returnCreateFormData);
        }

        return $returnCreateFormData;
    }
}
