<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterPage\Form\DataProvider;

use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Spryker\Client\Newsletter\NewsletterClientInterface;
use SprykerShop\Yves\NewsletterPage\Form\NewsletterSubscriptionForm;

class NewsletterSubscriptionFormDataProvider
{
    /**
     * @var \Spryker\Client\Newsletter\NewsletterClientInterface
     */
    protected $newsletterClient;

    /**
     * @param \Spryker\Client\Newsletter\NewsletterClientInterface $newsletterClient
     */
    public function __construct(NewsletterClientInterface $newsletterClient)
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

        return $subscriptionResultTransfer->getSubscriptionResults()[0]->getIsSuccess();
    }
}
