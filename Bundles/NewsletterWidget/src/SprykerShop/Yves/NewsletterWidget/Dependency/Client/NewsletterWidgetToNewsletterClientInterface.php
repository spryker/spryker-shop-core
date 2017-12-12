<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Dependency\Client;

use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;

interface NewsletterWidgetToNewsletterClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest);

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest);

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest);
}
