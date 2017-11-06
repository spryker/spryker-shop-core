<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\WishlistPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\WishlistPage\WishlistPageFactory getFactory()
 */
class AddToWishlistController extends AbstractController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction()
    {
        if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $this->view();
        }

        $wishlistCollection = $this->getFactory()
            ->getWishlistClient()
            ->getCustomerWishlistCollection();

        return $this->view([
            'wishlistCollection' => $wishlistCollection,
        ]);
    }
}
