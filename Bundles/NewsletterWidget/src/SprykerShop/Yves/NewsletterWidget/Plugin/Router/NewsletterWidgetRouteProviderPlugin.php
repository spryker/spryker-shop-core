<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class NewsletterWidgetRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_NEWSLETTER_SUBSCRIBE = 'newsletter/subscribe';

    public const ROUTE_NEWSLETTER_WIDGET_SUBSCRIBE = 'newsletter-widget/subscribe';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addNewsletterSubscribeRoute($routeCollection);
        $routeCollection = $this->addNewsletterWidgetSubscribeRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addNewsletterSubscribeRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/newsletter/subscribe', 'NewsletterWidget', 'Subscription', 'subscribeAction');
        $routeCollection->add(static::ROUTE_NEWSLETTER_SUBSCRIBE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addNewsletterWidgetSubscribeRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/newsletter-widget/subscribe', 'NewsletterWidget', 'SubscriptionWidget', 'subscribeAction');
        $routeCollection->add(static::ROUTE_NEWSLETTER_WIDGET_SUBSCRIBE, $route);
        return $routeCollection;
    }
}
