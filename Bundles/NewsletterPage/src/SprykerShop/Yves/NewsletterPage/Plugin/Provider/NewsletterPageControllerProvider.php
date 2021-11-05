<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use {@link \SprykerShop\Yves\NewsletterPage\Plugin\Router\NewsletterPageRouteProviderPlugin} instead.
 */
class NewsletterPageControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    public const ROUTE_CUSTOMER_NEWSLETTER = 'customer/newsletter';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addNewsletterRoute();
    }

    /**
     * @return $this
     */
    protected function addNewsletterRoute()
    {
        $this->createController('/{customer}/newsletter', static::ROUTE_CUSTOMER_NEWSLETTER, 'NewsletterPage', 'Newsletter', 'index')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer');

        return $this;
    }
}
