<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartToShoppingListWidget\Widet;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CartToShoppingListWidget\CartToShoppingListWidgetFactory getFactory()
 */
class CreateShoppingListFromCartWidget extends AbstractWidget
{
    /**
     * @param int $idQuote
     */
    public function __construct(int $idQuote)
    {
        $this->addParameter('form', $this->getFactory()->getCartFromShoppingListForm($idQuote)->createView());
        $this->addParameter('isVisible', $this->isVisible());
    }

    /**
     * @return bool
     */
    protected function isVisible(): bool
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!($customerTransfer && $customerTransfer->getCompanyUserTransfer())) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CreateShoppingListFromCartWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartToShoppingListWidget/views/create-shopping-list-from-cart/create-shopping-list-from-cart.twig';
    }
}
