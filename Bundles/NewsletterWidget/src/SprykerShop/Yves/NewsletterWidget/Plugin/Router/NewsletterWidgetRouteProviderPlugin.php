<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class NewsletterWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_NEWSLETTER_SUBSCRIBE = 'newsletter/subscribe';
    public const ROUTE_NEWSLETTER_WIDGET_SUBSCRIBE = 'newsletter-widget/subscribe';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addNewsletterSubscribeRoute($routeCollection);
        $routeCollection = $this->addNewsletterWidgetSubscribeRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return $this
     */
    protected function addNewsletterSubscribeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/newsletter/subscribe', 'NewsletterWidget', 'Subscription', 'subscribe');
        $routeCollection->add(static::ROUTE_NEWSLETTER_SUBSCRIBE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return $this
     */
    protected function addNewsletterWidgetSubscribeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/newsletter-widget/subscribe', 'NewsletterWidget', 'SubscriptionWidget', 'subscribe');
        $routeCollection->add(static::ROUTE_NEWSLETTER_WIDGET_SUBSCRIBE, $route);

        return $routeCollection;
    }
}
