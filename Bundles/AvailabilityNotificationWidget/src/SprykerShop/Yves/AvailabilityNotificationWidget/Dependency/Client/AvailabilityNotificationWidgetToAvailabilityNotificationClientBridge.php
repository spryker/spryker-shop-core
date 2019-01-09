<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client;

use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

class AvailabilityNotificationWidgetToAvailabilityNotificationClientBridge implements AvailabilityNotificationWidgetToAvailabilityNotificationClientInterface
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
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function subscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        return $this->availabilityNotificationClient->subscribe($availabilitySubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer
    {
        return $this->availabilityNotificationClient->unsubscribe($availabilitySubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceTransfer
     */
    public function checkExistence(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionExistenceTransfer
    {
        return $this->availabilityNotificationClient->checkExistence($availabilitySubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findAvailabilityNotification(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): ?AvailabilitySubscriptionTransfer
    {
        return $this->availabilityNotificationClient->findAvailabilityNotification($availabilitySubscriptionTransfer);
    }
}
