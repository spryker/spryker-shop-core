<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage\Dependency\Client;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;

class AvailabilityNotificationPageToAvailabilityNotificationClientBridge implements AvailabilityNotificationPageToAvailabilityNotificationClientInterface
{
    /**
     * @var \Spryker\Client\AvailabilityNotification\AvailabilityNotificationClientInterface
     */
    protected $availabilityNotificationClient;

    /**
     * @param \Spryker\Client\AvailabilityNotification\AvailabilityNotificationClientInterface $availabilityNotificationClient
     */
    public function __construct($availabilityNotificationClient)
    {
        $this->availabilityNotificationClient = $availabilityNotificationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionRequest
    ): AvailabilityNotificationSubscriptionResponseTransfer {
        return $this->availabilityNotificationClient->unsubscribeBySubscriptionKey($availabilityNotificationSubscriptionRequest);
    }
}
