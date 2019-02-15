<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\AvailabilityNotificationWidget\AvailabilityNotificationWidgetFactory getFactory()
 */
class AvailabilityNotificationSubscriptionWidget extends AbstractWidget
{
    protected const SESSION_AVAILABILITY_NOTIFICATION_EMAIL = 'availabilityNotificationEmail';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addParameter('isSubscribed', $this->getIsSubscribed($productViewTransfer));
        $this->addParameter('subscribeForm', $this->getSubscriptionForm($productViewTransfer));
        $this->addParameter('unsubscribeForm', $this->getUnsubscriptionForm($productViewTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AvailabilityNotificationSubscriptionWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@AvailabilityNotificationWidget/views/availability-subscription/availability-subscription.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    protected function getIsSubscribed(ProductViewTransfer $productViewTransfer): bool
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer === null) {
            return false;
        }

        return in_array($productViewTransfer->getSku(), $customerTransfer->getAvailabilityNotificationSubscriptionSkus());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Symfony\Component\Form\FormView
     */
    protected function getSubscriptionForm(ProductViewTransfer $productViewTransfer)
    {
        $formData = $this->getFactory()
            ->createAvailabilityNotificationSubscriptionFormDataProvider()
            ->getData($productViewTransfer);

        return $this->getFactory()
            ->createAvailabilityNotificationSubscriptionForm()
            ->setData($formData)
            ->createView();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Symfony\Component\Form\FormView
     */
    protected function getUnsubscriptionForm(ProductViewTransfer $productViewTransfer)
    {
        $formData = $this->getFactory()
            ->createAvailabilityUnsubscribeFormDataProvider()
            ->getData($productViewTransfer);

        return $this->getFactory()
            ->createAvailabilityUnsubscribeForm()
            ->setData($formData)
            ->createView();
    }
}
