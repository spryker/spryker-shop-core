<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Store;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToGlossaryStorageClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToMessengerClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface;

class ItemFetcher implements ItemFetcherInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED = 'reorder.info.restricted-product.removed';

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface $productStorageClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToMessengerClientInterface $messengerClient
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        CustomerReorderWidgetToProductBundleClientInterface $productBundleClient,
        CustomerReorderWidgetToProductStorageClientInterface $productStorageClient,
        CustomerReorderWidgetToMessengerClientInterface $messengerClient,
        CustomerReorderWidgetToGlossaryStorageClientInterface $glossaryStorageClient,
        Store $store
    ) {
        $this->productBundleClient = $productBundleClient;
        $this->productStorageClient = $productStorageClient;
        $this->messengerClient = $messengerClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->store = $store;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getAll(OrderTransfer $orderTransfer): array
    {
        $sourceItems = $this->getOrderItemsTransfer($orderTransfer);

        return $sourceItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] $idOrderItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getByIds(OrderTransfer $orderTransfer, array $idOrderItems): array
    {
        $sourceItems = $this->getOrderItemsTransfer($orderTransfer);
        $filteredItems = $this->filterById($sourceItems, $idOrderItems);

        return $filteredItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItemsTransfer(OrderTransfer $orderTransfer): array
    {
        $itemTransfers = $this->productBundleClient
            ->getItemsWithBundlesItems(
                (new QuoteTransfer())
                    ->setItems($orderTransfer->getItems())
                    ->setBundleItems($orderTransfer->getBundleItems())
            );

        return $this->cleanUpItems($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function cleanUpItems(array $itemTransfers): array
    {
        $cleanItems = [];
        foreach ($itemTransfers as $itemTransfer) {
            if ($this->productStorageClient->isProductConcreteRestricted($itemTransfer->getId())) {
                $this->addInfoMessage($itemTransfer);

                continue;
            }

            $idSaleOrderItem = $itemTransfer->getIdSalesOrderItem();
            $itemTransfer->setIdSalesOrderItem(null);
            $itemTransfer->setIsOrdered(false);
            $itemTransfer = $this->cleanUpProductOptions($itemTransfer);

            $cleanItems[$idSaleOrderItem] = $itemTransfer;
        }

        return $cleanItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addInfoMessage(ItemTransfer $itemTransfer): void
    {
        $this->messengerClient->addInfoMessage(
            $this->glossaryStorageClient->translate(
                static::MESSAGE_INFO_RESTRICTED_PRODUCT_REMOVED,
                $this->store->getCurrentLocale(),
                [static::MESSAGE_PARAM_SKU => $itemTransfer->getSku()]
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function cleanUpProductOptions(ItemTransfer $itemTransfer): ItemTransfer
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionTransfer->setIsOrdered(false);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param int[] $idOrderItems
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function filterById(array $itemTransfers, array $idOrderItems): array
    {
        $allowed_keys = array_flip($idOrderItems);
        $filteredItems = array_intersect_key($itemTransfers, $allowed_keys);

        return $filteredItems;
    }
}
