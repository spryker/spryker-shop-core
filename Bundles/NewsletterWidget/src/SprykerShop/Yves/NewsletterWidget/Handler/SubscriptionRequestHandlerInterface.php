<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Handler;

use Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer;

interface SubscriptionRequestHandlerInterface
{
    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer|null
     */
    public function subscribe(string $email): ?NewsletterSubscriptionResultTransfer;
}
