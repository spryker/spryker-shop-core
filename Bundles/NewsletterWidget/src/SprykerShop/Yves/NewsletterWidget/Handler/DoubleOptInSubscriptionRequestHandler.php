<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Handler;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Spryker\Shared\Newsletter\NewsletterConstants;
use SprykerShop\Yves\NewsletterWidget\Dependency\Client\NewsletterWidgetToNewsletterClientInterface;

class DoubleOptInSubscriptionRequestHandler implements SubscriptionRequestHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\NewsletterWidget\Dependency\Client\NewsletterWidgetToNewsletterClientInterface
     */
    protected $newsletterClient;

    /**
     * @param \SprykerShop\Yves\NewsletterWidget\Dependency\Client\NewsletterWidgetToNewsletterClientInterface $newsletterClient
     */
    public function __construct(NewsletterWidgetToNewsletterClientInterface $newsletterClient)
    {
        $this->newsletterClient = $newsletterClient;
    }

    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer|null
     */
    public function subscribe(string $email): ?NewsletterSubscriptionResultTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setEmail($email);

        $request = $this->createNewsletterSubscriptionRequest($customerTransfer);
        $subscriptionResponse = $this->newsletterClient
            ->subscribeWithDoubleOptIn($request);

        if ($subscriptionResponse->getSubscriptionResults()->count() === 0) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer $newsletterSubscriptionResultTransfer */
        $newsletterSubscriptionResultTransfer = $subscriptionResponse->getSubscriptionResults()->getIterator()->current();

        return $newsletterSubscriptionResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string|null $subscriberKey
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer
     */
    protected function createNewsletterSubscriptionRequest(CustomerTransfer $customerTransfer, ?string $subscriberKey = null): NewsletterSubscriptionRequestTransfer
    {
        $subscriptionRequest = new NewsletterSubscriptionRequestTransfer();

        $subscriberTransfer = new NewsletterSubscriberTransfer();
        $subscriberTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $subscriberTransfer->setEmail($customerTransfer->getEmail());
        $subscriberTransfer->setSubscriberKey($subscriberKey);

        $subscriptionRequest->setNewsletterSubscriber($subscriberTransfer);
        $subscriptionRequest->addSubscriptionType((new NewsletterTypeTransfer())
            ->setName(NewsletterConstants::DEFAULT_NEWSLETTER_TYPE));

        return $subscriptionRequest;
    }
}
