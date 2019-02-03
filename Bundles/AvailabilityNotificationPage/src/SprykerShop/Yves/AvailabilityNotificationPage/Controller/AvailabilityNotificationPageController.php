<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage\Controller;

use Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\AvailabilityNotificationPage\Plugin\Provider\AvailabilityNotificationPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\AvailabilityNotificationPage\AvailabilityNotificationPageFactory getFactory()
 */
class AvailabilityNotificationPageController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function unsubscribeAction(Request $request): View
    {
        $data = $this->executeUnsubscribeAction($request);

        return $this->view($data, [], '@AvailabilityNotificationPage/views/availability-notification/unsubscribe.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executeUnsubscribeAction(Request $request): array
    {
        $subscriptionKey = $request->query->get(AvailabilityNotificationPageControllerProvider::PARAM_SUBSCRIPTION_KEY);

        $availabilitySubscriptionRequestTransfer = (new AvailabilitySubscriptionRequestTransfer())->setSubscriptionKey($subscriptionKey);

        $availabilitySubscriptionResponseTransfer = $this->getFactory()->getAvailabilityNotificationClient()->findAvailabilitySubscription($availabilitySubscriptionRequestTransfer);

        if ($availabilitySubscriptionResponseTransfer->getAvailabilitySubscription() === null) {
            throw new NotFoundHttpException('Subscription doesn\'t exist');
        }

        $locale = $availabilitySubscriptionResponseTransfer->getAvailabilitySubscription()->getLocale();

        $availabilitySubscriptionResponseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->unsubscribe($availabilitySubscriptionResponseTransfer->getAvailabilitySubscription());

        if ($availabilitySubscriptionResponseTransfer->getIsSuccess() === false) {
            throw new NotFoundHttpException($availabilitySubscriptionResponseTransfer->getErrorMessage());
        }

        $productAttributes = $this->getProductAttributes($availabilitySubscriptionResponseTransfer->getProduct(), $locale);

        return ['productName' => $productAttributes['name'] ?? ''];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getProductAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer
    ): array {
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttributes->toArray();
            }
        }

        return [];
    }
}
