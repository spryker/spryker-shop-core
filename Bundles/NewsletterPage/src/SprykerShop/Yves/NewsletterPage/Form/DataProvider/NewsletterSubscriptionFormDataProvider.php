<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterPage\Form\DataProvider;

use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use SprykerShop\Yves\NewsletterPage\Dependency\Client\NewsletterPageToNewsletterClientInterface;
use SprykerShop\Yves\NewsletterPage\Form\NewsletterSubscriptionForm;

class NewsletterSubscriptionFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\NewsletterPage\Dependency\Client\NewsletterPageToNewsletterClientInterface
     */
    protected $newsletterClient;

    /**
     * @param \SprykerShop\Yves\NewsletterPage\Dependency\Client\NewsletterPageToNewsletterClientInterface $newsletterClient
     */
    public function __construct(NewsletterPageToNewsletterClientInterface $newsletterClient)
    {
        $this->newsletterClient = $newsletterClient;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return array
     */
    public function getData(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer)
    {
        return [
            NewsletterSubscriptionForm::FIELD_SUBSCRIBE => $this->isSubscribed($newsletterSubscriptionRequestTransfer),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return bool
     */
    protected function isSubscribed(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer): bool
    {
        $subscriptionResultTransfer = $this->newsletterClient->checkSubscription($newsletterSubscriptionRequestTransfer);

        /** @var array<\Generated\Shared\Transfer\NewsletterSubscriptionResultTransfer> $newsletterSubscriptionResults */
        $newsletterSubscriptionResults = $subscriptionResultTransfer->getSubscriptionResults();

        return $newsletterSubscriptionResults[0]->getIsSuccess();
    }
}
