<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterPage\Dependency\Client;

use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;

class NewsletterPageToNewsletterClientBridge implements NewsletterPageToNewsletterClientInterface
{
    /**
     * @var \Spryker\Client\Newsletter\NewsletterClientInterface
     */
    protected $newsletterClient;

    /**
     * @param \Spryker\Client\Newsletter\NewsletterClientInterface $newsletterClient
     */
    public function __construct($newsletterClient)
    {
        $this->newsletterClient = $newsletterClient;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->newsletterClient->subscribeWithSingleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->newsletterClient->subscribeWithDoubleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function unsubscribe(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->newsletterClient->unsubscribe($newsletterSubscriptionRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        return $this->newsletterClient->checkSubscription($newsletterUnsubscriptionRequest);
    }
}
