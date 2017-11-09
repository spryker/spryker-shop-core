<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Provider;

use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractServiceProvider;
use Silex\Application;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartServiceProvider extends AbstractServiceProvider
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
