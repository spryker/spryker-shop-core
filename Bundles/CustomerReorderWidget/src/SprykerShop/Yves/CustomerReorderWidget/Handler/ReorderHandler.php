<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 2/2/18
 * Time: 14:09
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;


use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
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
     * @var CustomerReorderWidgetToCartClientInterface
     */
    private $cartClient;
    /**
     * @var CustomerReorderWidgetToSalesClientInterface
     */
    private $salesClient;

    /**
     * @param CustomerReorderWidgetToCartClientInterface $cartClient
     * @param CustomerReorderWidgetToSalesClientInterface $salesClient
     */
    public function __construct(
        CustomerReorderWidgetToCartClientInterface $cartClient,
        CustomerReorderWidgetToSalesClientInterface $salesClient
    )
    {
        $this->cartClient = $cartClient;
        $this->salesClient = $salesClient;
    }

    public function reorder($idSalesOrder, CustomerTransfer $customerTransfer)
    {
        $items = $this->getOrderItemsTransfer($idSalesOrder, $customerTransfer)
            ->getArrayCopy();

        $this->updateCart($items);
    }

    public function reorderItems($idSalesOrder, CustomerTransfer $customerTransfer, $idOrderItems)
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

    protected function getOrderItemsTransfer($idSalesOrder, CustomerTransfer $customerTransfer): \ArrayObject
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder($idSalesOrder)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        $orderTransfer = $this->salesClient
            ->getOrderDetails($orderTransfer);

        return $orderTransfer->getItems();
    }

    protected function updateCart(array $orderItems)
    {
        $quote = new QuoteTransfer();
        $this->cartClient->storeQuote($quote);

        $quoteTransfer = $this->cartClient->addItems($orderItems);
        $this->cartClient->storeQuote($quoteTransfer);
    }
}
