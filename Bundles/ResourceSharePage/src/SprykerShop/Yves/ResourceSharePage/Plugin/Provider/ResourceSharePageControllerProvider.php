<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class ResourceSharePageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_RESOURCE_SHARE_LINK = 'link';

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
     * @uses QuickOrderController::indexAction()
     *
     * @return $this
     */
    protected function addLinkRoute()
    {
        $this->createController('/{resourceShareLink}/{resourceShareUuid}', static::ROUTE_RESOURCE_SHARE_LINK, 'ResourceSharePage', 'Link')
            ->value('resourceShareLink', 'link');

        return $this;
    }
}
