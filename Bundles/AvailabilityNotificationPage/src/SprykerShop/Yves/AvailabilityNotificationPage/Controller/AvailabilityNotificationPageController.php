<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage\Controller;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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
        $data = $this->executeUnsubscribeByKeyAction($subscriptionKey);

        return $this->view($data, [], '@AvailabilityNotificationPage/views/availability-notification/unsubscribe.twig');
    }

    /**
     * @param string $subscriptionKey
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function executeUnsubscribeByKeyAction(string $subscriptionKey): array
    {
        $availabilitySubscriptionTransfer = (new AvailabilitySubscriptionTransfer())->setSubscriptionKey($subscriptionKey);

        $availabilitySubscriptionResponseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->unsubscribe($availabilitySubscriptionTransfer);

        if ($availabilitySubscriptionResponseTransfer->getIsSuccess() === false) {
            throw new NotFoundHttpException($availabilitySubscriptionResponseTransfer->getErrorMessage());
        }

        $this->removeAvailabilitySubscriptionFromCustomer($availabilitySubscriptionResponseTransfer->getProduct()->getSku());

        return ['productName' => $this->getProductName($availabilitySubscriptionResponseTransfer->getProduct())];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    protected function getProductName(ProductConcreteTransfer $productConcreteTransfer): string
    {
        $locale = $this->getLocale();
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getLocaleName() === $locale) {
                $attributes = $localizedAttributes->toArray();
                return $attributes['name'] ?? '';
            }
        }

        return '';
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function removeAvailabilitySubscriptionFromCustomer(string $sku): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $availabilitySubscriptions = $customerTransfer->getAvailabilitySubscriptions();

        foreach ($availabilitySubscriptions as $key => $availabilitySubscription) {
            if ($availabilitySubscription->getSku() === $sku) {
                unset($availabilitySubscriptions[$key]);
                break;
            }
        }

        $customerTransfer->setAvailabilitySubscriptions($availabilitySubscriptions);
    }
}
