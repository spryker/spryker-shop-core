<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @deprecated Use `SprykerShop\Yves\CartPage\Plugin\Twig\CartTwigPlugin` instead.
 *
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['cart.quantity'] = $app->share(function () {
            $quantity = $this->getFactory()
                ->getCartClient()
                ->getItemCount();

            return $quantity;
        });
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
