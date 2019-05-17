<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class NewsletterWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_NEWSLETTER_SUBSCRIBE = 'newsletter/subscribe';

    protected const ROUTE_NEWSLETTER_WIDGET_SUBSCRIBE = 'newsletter-widget/subscribe';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addNewsletterSubscribeRoute($routeCollection);
        $routeCollection = $this->addNewsletterWidgetSubscribeRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addNewsletterSubscribeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/newsletter/subscribe', 'NewsletterWidget', 'Subscription', 'subscribeAction');
        $routeCollection->add(static::ROUTE_NEWSLETTER_SUBSCRIBE, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addNewsletterWidgetSubscribeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/newsletter-widget/subscribe', 'NewsletterWidget', 'SubscriptionWidget', 'subscribeAction');
        $routeCollection->add(static::ROUTE_NEWSLETTER_WIDGET_SUBSCRIBE, $route);

        return $routeCollection;
    }
}
