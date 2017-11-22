<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Shared\Newsletter\NewsletterConstants;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerController extends AbstractCustomerController
{
    const ORDER_LIST_LIMIT = 5;
    const ORDER_LIST_SORT_FIELD = 'created_at';
    const ORDER_LIST_SORT_DIRECTION = 'DESC';

    const KEY_BILLING = 'billing';
    const KEY_SHIPPING = 'shipping';

    /**
     * @return View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $loggedInCustomerTransfer = $this->getLoggedInCustomerTransfer();
        $customerTransfer = $this
            ->getFactory()
            ->getCustomerClient()
            ->getCustomerByEmail($loggedInCustomerTransfer);

        if (!$customerTransfer->getIdCustomer()) {
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_LOGOUT);
        }

        $orderListTransfer = $this->createOrderListTransfer($customerTransfer);
        $orderList = $this->getFactory()->getSalesClient()->getPaginatedOrder($orderListTransfer);

        $data = [
            'customer' => $customerTransfer,
            'orderList' => $orderList->getOrders(),
            'addresses' => $this->getDefaultAddresses($customerTransfer),
            'isSubscribed' => $this->getIsSubscribed($customerTransfer),
        ];

        return $this->view($data, $this->getFactory()->getCustomerOverviewWidgetPlugins());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    protected function createOrderListTransfer(CustomerTransfer $customerTransfer)
    {
        $filterTransfer = $this->createFilterTransfer();

        $orderListTransfer = new OrderListTransfer();
        $orderListTransfer->setIdCustomer($customerTransfer->getIdCustomer());
        $orderListTransfer->setFilter($filterTransfer);

        return $orderListTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer()
    {
        $filterTransfer = new FilterTransfer();

        $filterTransfer->setLimit(self::ORDER_LIST_LIMIT);
        $filterTransfer->setOffset(0);
        $filterTransfer->setOrderBy(self::ORDER_LIST_SORT_FIELD);
        $filterTransfer->setOrderDirection(self::ORDER_LIST_SORT_DIRECTION);

        return $filterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function getDefaultAddresses(CustomerTransfer $customerTransfer)
    {
        $addressesTransfer = $customerTransfer->getAddresses();
        if ($addressesTransfer === null) {
            return [];
        }

        $addresses = [];
        foreach ($addressesTransfer->getAddresses() as $address) {
            if ($customerTransfer->getDefaultBillingAddress() === $address->getIdCustomerAddress()) {
                $addresses[self::KEY_BILLING] = $address;
            }

            if ($customerTransfer->getDefaultShippingAddress() === $address->getIdCustomerAddress()) {
                $addresses[self::KEY_SHIPPING] = $address;
            }
        }

        return $addresses;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    protected function getIsSubscribed(CustomerTransfer $customerTransfer)
    {
        $subscriptionRequestTransfer = new NewsletterSubscriptionRequestTransfer();

        $subscriberTransfer = new NewsletterSubscriberTransfer();
        $subscriberTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $subscriberTransfer->setEmail($customerTransfer->getEmail());
        $subscriptionRequestTransfer->setNewsletterSubscriber($subscriberTransfer);

        $newsletterTypeTransfer = new NewsletterTypeTransfer();
        $newsletterTypeTransfer->setName(NewsletterConstants::DEFAULT_NEWSLETTER);

        $subscriptionRequestTransfer->addSubscriptionType($newsletterTypeTransfer);

        $subscriptionResponseTransfer = $this->getFactory()
            ->getNewsletterClient()
            ->checkSubscription($subscriptionRequestTransfer);

        $result = current($subscriptionResponseTransfer->getSubscriptionResults());

        return $result->getisSuccess();
    }
}
