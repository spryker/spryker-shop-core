<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartSharePage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class PersistentCartSharePageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_CART_PREVIEW = 'cart/preview';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addPersistentCartSharePageRoute();
    }

    /**
     * @return $this
     */
    protected function addPersistentCartSharePageRoute()
    {
        $this->createController('{persistentCartShare}/preview/{resourceShareUuid}', self::ROUTE_CART_PREVIEW, 'PersistentCartSharePage', 'Cart', 'preview')
            ->value('persistentCartShare', 'cart');

        return $this;
    }
}
