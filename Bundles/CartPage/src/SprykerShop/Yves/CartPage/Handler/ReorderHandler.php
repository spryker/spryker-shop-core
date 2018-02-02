<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 2/2/18
 * Time: 14:09
 */

namespace SprykerShop\Yves\CartPage\Handler;


use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToSalesClientInterface;

/**
 * Class ReorderHandler
 * @package SprykerShop\Yves\CartPage\Handler
 *
 * @method CustomerTransfer getLoggedInCustomerTransfer()
 */
class ReorderHandler
{
    /**
     * @var CartPageToCartClientInterface
     */
    private $cartClient;
    /**
     * @var CartPageToSalesClientInterface
     */
    private $salesClient;

    /**
     * @param CartPageToCartClientInterface $cartClient
     * @param CartPageToSalesClientInterface $salesClient
     */
    public function __construct(
        CartPageToCartClientInterface $cartClient,
        CartPageToSalesClientInterface $salesClient
    )
    {
        $this->cartClient = $cartClient;
        $this->salesClient = $salesClient;
    }

    public function reorder($idSalesOrder, CustomerTransfer $customerTransfer)
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder($idSalesOrder)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        $orderTransfer = $this->salesClient
            ->getOrderDetails($orderTransfer);

        $quote = new QuoteTransfer();
        $this->cartClient->storeQuote($quote);

        $items = $orderTransfer->getItems();

        $quoteTransfer = $this->cartClient->addItems($items->getArrayCopy());
        $this->cartClient->storeQuote($quoteTransfer);
    }
}
