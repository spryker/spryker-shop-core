<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Widget;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Spryker\Shared\Newsletter\NewsletterConstants;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\NewsletterWidget\NewsletterWidgetFactory getFactory()
 */
class NewsletterSubscriptionSummaryWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     */
    public function __construct(CustomerTransfer $customerTransfer)
    {
        $this->addParameter('isSubscribed', $this->getIsSubscribed($customerTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'NewsletterSubscriptionSummaryWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@NewsletterWidget/views/newsletter-subscription-summary/newsletter-subscription-summary.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function getIsSubscribed(CustomerTransfer $customerTransfer): bool
    {
        $subscriptionRequestTransfer = new NewsletterSubscriptionRequestTransfer();

        $subscriberTransfer = new NewsletterSubscriberTransfer();
        $subscriberTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $subscriberTransfer->setEmail($customerTransfer->getEmail());
        $subscriptionRequestTransfer->setNewsletterSubscriber($subscriberTransfer);

        $newsletterTypeTransfer = new NewsletterTypeTransfer();
        $newsletterTypeTransfer->setName(NewsletterConstants::DEFAULT_NEWSLETTER_TYPE);

        $subscriptionRequestTransfer->addSubscriptionType($newsletterTypeTransfer);

        $subscriptionResponseTransfer = $this->getFactory()
            ->getNewsletterClient()
            ->checkSubscription($subscriptionRequestTransfer);

        $result = $subscriptionResponseTransfer->getSubscriptionResults()[0];

        return $result->getIsSuccess();
    }
}
