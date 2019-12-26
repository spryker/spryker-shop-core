<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Form\FormHandler;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Symfony\Component\Form\FormInterface;

interface CreateFromCartHandlerInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $cartToShoppingListForm
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingListFromCart(FormInterface $cartToShoppingListForm): ShoppingListTransfer;
}
