<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListWidgetAsyncController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function navigationWidgetViewAction(Request $request): View
    {
        return $this->view(
            $this->getViewData(),
            [],
            '@ShoppingListWidget/views/shopping-list-shop-list-async/shopping-list-shop-list-async.twig',
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'shoppingListCollection' => $this->getFactory()
                ->getShoppingListSessionClient()
                ->getCustomerShoppingListCollection(),
        ];
    }
}
