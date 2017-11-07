<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\NewsletterWidget;

use Spryker\Client\Newsletter\NewsletterClient;
use Spryker\Client\Newsletter\NewsletterFactory as SprykerNewsletterFactory;
use SprykerShop\Client\NewsletterWidget\Model\SubscriptionRequestLogic;

class NewsletterWidgetFactory extends SprykerNewsletterFactory
{
    /**
     * @return \SprykerShop\Client\NewsletterWidget\Model\SubscriptionRequestLogicInterface
     */
    public function createSubscriptionRequestLogic()
    {
        return new SubscriptionRequestLogic();
    }

    /**
     * @return \Spryker\Client\Newsletter\NewsletterClientInterface
     */
    public function getNewsletterClient()
    {
        return new NewsletterClient(); // TODO: get from dependency provider
    }
}
