<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationPage\Dependency\Client;

use Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;

interface AvailabilityNotificationPageToAvailabilityNotificationClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKey(AvailabilitySubscriptionTransfer $availabilityNotificationSubscriptionRequest): AvailabilitySubscriptionResponseTransfer;
}
