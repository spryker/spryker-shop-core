<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class AddToShoppingListController extends AbstractController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction()
    {
        if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $this->view();
        }

        $shoppingListCollection = $this->getFactory()
            ->getShoppingListClient()
            ->getCustomerShoppingListCollection();

        return $this->view([
            'shoppingListCollection' => $shoppingListCollection,
        ]);
    }
}
