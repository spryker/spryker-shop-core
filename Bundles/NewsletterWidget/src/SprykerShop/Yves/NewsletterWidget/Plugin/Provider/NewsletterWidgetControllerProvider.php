<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\NewsletterWidget\Plugin\Router\NewsletterWidgetRouteProviderPlugin` instead.
 */
class NewsletterWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_NEWSLETTER_SUBSCRIBE = 'newsletter/subscribe';

    public const ROUTE_NEWSLETTER_WIDGET_SUBSCRIBE = 'newsletter-widget/subscribe';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addNewsletterSubscribeRoute()
            ->addNewsletterWidgetSubscribeRoute();
    }

    /**
     * @return $this
     */
    protected function addNewsletterSubscribeRoute()
    {
        $this->createController('/{newsletter}/subscribe', self::ROUTE_NEWSLETTER_SUBSCRIBE, 'NewsletterWidget', 'Subscription', 'subscribe')
            ->assert('newsletter', $this->getAllowedLocalesPattern() . 'newsletter|newsletter')
            ->value('newsletter', 'newsletter');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addNewsletterWidgetSubscribeRoute()
    {
        $this->createController('/{newsletter-widget}/subscribe', self::ROUTE_NEWSLETTER_WIDGET_SUBSCRIBE, 'NewsletterWidget', 'SubscriptionWidget', 'subscribe')
            ->assert('newsletter-widget', $this->getAllowedLocalesPattern() . 'newsletter-widget|newsletter-widget')
            ->value('newsletter-widget', 'newsletter-widget');

        return $this;
    }
}
