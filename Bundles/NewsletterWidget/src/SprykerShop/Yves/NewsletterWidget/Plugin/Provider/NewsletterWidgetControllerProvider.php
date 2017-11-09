<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\NewsletterWidget\Plugin\Provider;

use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class NewsletterWidgetControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_NEWSLETTER_SUBSCRIBE = 'newsletter/subscribe';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{newsletter}/subscribe', self::ROUTE_NEWSLETTER_SUBSCRIBE, 'NewsletterWidget', 'Subscription', 'subscribe')
            ->assert('newsletter', $allowedLocalesPattern . 'newsletter|newsletter')
            ->value('newsletter', 'newsletter');
    }
}
