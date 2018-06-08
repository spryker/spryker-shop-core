<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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

        $data = [
            'wishlistCollection' => $wishlistCollection,
        ];

        return $this->view($data, [], '@WishlistPage/views/wishlist-selection/wishlist-selection.twig');
    }
}
