<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\ResourceSharePage\Plugin\Router\ResourceSharePageRouteProviderPlugin` instead.
 */
class ResourceSharePageControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_RESOURCE_SHARE_LINK = 'resource-share/link';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addLinkRoute();
    }

    /**
     * @uses \SprykerShop\Yves\ResourceSharePage\Controller\LinkController::indexAction()
     *
     * @return $this
     */
    protected function addLinkRoute()
    {
        $this->createController('/{resourceShareLink}/{resourceShareUuid}', static::ROUTE_RESOURCE_SHARE_LINK, 'ResourceSharePage', 'Link')
            ->assert('resourceShareLink', $this->getAllowedLocalesPattern() . 'resource-share/link|resource-share/link')
            ->value('resourceShareLink', 'resource-share/link');

        return $this;
    }
}
