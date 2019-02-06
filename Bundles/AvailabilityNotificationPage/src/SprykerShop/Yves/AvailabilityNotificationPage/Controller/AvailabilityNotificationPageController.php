<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage\Controller;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\AvailabilityNotificationPage\AvailabilityNotificationPageFactory getFactory()
 */
class AvailabilityNotificationPageController extends AbstractController
{
    /**
     * @param string $subscriptionKey
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function unsubscribeByKeyAction(string $subscriptionKey)
    {
        $this->executeUnsubscribeByKeyAction($subscriptionKey);

        return $this->view([], [], '@AvailabilityNotificationPage/views/availability-notification/unsubscribe.twig');
    }

    /**
     * @param string $subscriptionKey
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function executeUnsubscribeByKeyAction(string $subscriptionKey): void
    {
        $availabilityNotificationSubscriptionTransfer = (new AvailabilityNotificationSubscriptionTransfer())->setSubscriptionKey($subscriptionKey);

        $availabilityNotificationSubscriptionResponseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->unsubscribeBySubscriptionKey($availabilityNotificationSubscriptionTransfer);

        if ($availabilityNotificationSubscriptionResponseTransfer->getIsSuccess() === false) {
            throw new NotFoundHttpException($availabilityNotificationSubscriptionResponseTransfer->getErrorMessage());
        }

        $this->removeAvailabilityNotificationSubscriptionFromCustomer($availabilityNotificationSubscriptionResponseTransfer->getAvailabilityNotificationSubscription()->getSku());
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function removeAvailabilityNotificationSubscriptionFromCustomer(string $sku): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer === null) {
            return;
        }

        $availabilityNotificationSubscriptionSkus = $customerTransfer->getAvailabilityNotificationSubscriptionSkus();

        $key = array_search($sku, $availabilityNotificationSubscriptionSkus);

        if ($key !== false) {
            unset($availabilityNotificationSubscriptionSkus[$key]);
        }

        $customerTransfer->setAvailabilityNotificationSubscriptionSkus($availabilityNotificationSubscriptionSkus);
    }
}
