<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Client;

use Generated\Shared\Transfer\FindAvailabilitySubscriptionRequestTransfer;
use Generated\Shared\Transfer\FindAvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

interface AvailabilityNotificationWidgetToAvailabilityNotificationClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function subscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribe(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\FindAvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FindAvailabilitySubscriptionResponseTransfer
     */
    public function findAvailabilitySubscription(FindAvailabilitySubscriptionRequestTransfer $availabilitySubscriptionExistenceRequestTransfer): FindAvailabilitySubscriptionResponseTransfer;
}
