<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Subscriber;

interface SubscriberInterface
{
    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer
     */
    public function subscribe(string $email);
}
