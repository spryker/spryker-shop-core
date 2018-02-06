<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 2/2/18
 * Time: 14:09
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;


use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface;

/**
 * Class ReorderHandler
 * @package SprykerShop\Yves\CartPage\Handler
 *
 * @method CustomerTransfer getLoggedInCustomerTransfer()
 */
class ReorderHandler
{
    /**
     * Name of field in grouped items.
     * Softlink to ProductBundle
     * @see \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
     */
    const BUNDLE_PRODUCT = 'bundleProduct';
    /**
     * @var CustomerReorderWidgetToCartClientInterface
     */
    protected $cartClient;
    /**
     * @var CustomerReorderWidgetToSalesClientInterface
     */
    protected $salesClient;
    /**
     * @var CustomerReorderWidgetToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @param CustomerReorderWidgetToCartClientInterface $cartClient
     * @param CustomerReorderWidgetToSalesClientInterface $salesClient
     * @param CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient,
        CustomerReorderWidgetToSalesClientInterface $salesClient,
        CustomerReorderWidgetToProductBundleClientInterface $productBundleClient
    )
    {
        $this->cartClient = $cartClient;
        $this->salesClient = $salesClient;
        $this->productBundleClient = $productBundleClient;
    }

    public function reorder($idSalesOrder, CustomerTransfer $customerTransfer): void
    {
        $items = $this->getOrderItemsTransfer($idSalesOrder, $customerTransfer);

        $this->updateCart($items);
    }

    public function reorderItems($idSalesOrder, CustomerTransfer $customerTransfer, $idOrderItems): void
    {
        $items = $this->getOrderItemsTransfer($idSalesOrder, $customerTransfer);

        $itemsToAdd = [];
        foreach ($items as $item) {
            if (!$idOrderItems) {
                break;
            }
            if (!in_array($item->getId(), $idOrderItems)) {
                continue;
            }

            $itemsToAdd[] = $item;
            $key = array_search($item->getId(), $idOrderItems);
            unset($idOrderItems[$key]);
        }

        //if (!empty($idOrderItems)) show error

        $this->updateCart($itemsToAdd);
    }

    protected function getOrderItemsTransfer($idSalesOrder, CustomerTransfer $customerTransfer): array
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder($idSalesOrder)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        $orderTransfer = $this->salesClient
            ->getOrderDetails($orderTransfer);

        $items = $this->productBundleClient
            ->getGroupedBundleItems($orderTransfer->getItems(), $orderTransfer->getBundleItems());
        $items = $this->getProductsFromBundles($items);

        return $items;
    }

    protected function updateCart(array $orderItems)
    {
        $quote = new QuoteTransfer();
        $this->cartClient->storeQuote($quote);

        $quoteTransfer = $this->cartClient->addItems($orderItems);
        $this->cartClient->storeQuote($quoteTransfer);
    }

    protected function getProductsFromBundles(array $groupedItems): array
    {
        $items = array_map(function ($groupedItem) {
            return $groupedItem instanceof ItemTransfer ? $groupedItem : $groupedItem[static::BUNDLE_PRODUCT];
        }, $groupedItems);

        return $items;
    }
}
