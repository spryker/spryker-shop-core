<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage\Controller;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\ProductStorageConfig;
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
     * @return array
     */
    protected function executeUnsubscribeAction(Request $request): array
    {
        $subscriptionKey = $request->query->get(AvailabilityNotificationPageControllerProvider::PARAM_SUBSCRIPTION_KEY);

        $availabilitySubscriptionExistenceRequestTransfer = (new AvailabilitySubscriptionExistenceRequestTransfer())->setSubscriptionKey($subscriptionKey);

        $availabilitySubscriptionExistenceResponseTransfer = $this->getFactory()->getAvailabilityNotificationClient()->checkExistence($availabilitySubscriptionExistenceRequestTransfer);

        if ($availabilitySubscriptionExistenceResponseTransfer->getAvailabilitySubscription() === null) {
            throw new NotFoundHttpException('Subscription doesn\'t exist');
        }

        $availabilitySubscriptionResponseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->unsubscribe($availabilitySubscriptionExistenceResponseTransfer->getAvailabilitySubscription());

        if ($availabilitySubscriptionResponseTransfer->getIsSuccess() === false) {
            throw new NotFoundHttpException($availabilitySubscriptionResponseTransfer->getErrorMessage());
        }

        $productViewTransfer = $this->createProductViewTransfer($availabilitySubscriptionResponseTransfer);

        return ['product' => $productViewTransfer];
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer $availabilitySubscriptionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function createProductViewTransfer(
        AvailabilitySubscriptionResponseTransfer $availabilitySubscriptionResponseTransfer
    ): ProductViewTransfer {
        $locale = $this->getFactory()->getLocaleClient()->getCurrentLocale();

        $availabilitySubscriptionResponseTransfer->getProduct()->requireIdProductConcrete();

        $idProductConcrete = $availabilitySubscriptionResponseTransfer->getProduct()->getIdProductConcrete();

        $productStorageData = $this->getFactory()
            ->getProductStorageClient()
            ->findProductConcreteStorageData($idProductConcrete, $locale);

        $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP] = (new AttributeMapStorageTransfer())->toArray();

        return $this->getFactory()
            ->getProductStorageClient()
            ->mapProductStorageData($productStorageData, $locale);
    }
}
