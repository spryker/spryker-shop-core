<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Form\DataProvider;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToCustomerClientInterface;

class AvailabilityUnsubscriptionFormDataProvider implements AvailabilityUnsubscriptionFormDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client\AvailabilityNotificationWidgetToCustomerClientInterface $customerClient
     */
    public function __construct(AvailabilityNotificationWidgetToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    public function getData(ProductViewTransfer $productViewTransfer): AvailabilitySubscriptionTransfer
    {
        return (new AvailabilitySubscriptionTransfer())->setSku($productViewTransfer->getSku());
    }
}
