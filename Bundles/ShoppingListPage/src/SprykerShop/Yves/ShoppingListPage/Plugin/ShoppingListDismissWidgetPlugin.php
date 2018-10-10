<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Plugin;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShoppingListDismissWidgetPlugin extends AbstractWidgetPlugin
{
    public const NAME = 'ShoppingListDismissWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function initialize(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->addShoppingListParam($shoppingListTransfer);
        $this->addIsCustomerShoppingListOwnerParam($shoppingListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListPage/views/shopping-list-dismiss-link/shopping-list-dismiss-link.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function addShoppingListParam(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->addParameter('shoppingList', $shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function addIsCustomerShoppingListOwnerParam(ShoppingListTransfer $shoppingListTransfer): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        $this->addParameter('isOwner', $shoppingListTransfer->getCustomerReference() === $customerTransfer->getCustomerReference());
    }
}
