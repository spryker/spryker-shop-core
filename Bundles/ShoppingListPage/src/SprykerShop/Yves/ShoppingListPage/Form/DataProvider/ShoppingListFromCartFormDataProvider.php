<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\DataProvider;

use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;

class ShoppingListFromCartFormDataProvider
{
    use PermissionAwareTrait;

    protected const GLOSSARY_KEY_CART_ADD_TO_SHOPPING_LIST_FORM_ADD_NEW = 'cart.add-to-shopping-list.form.add_new';

    /**
     * @uses \SprykerShop\Yves\ShoppingListPage\Form\ShoppingListFromCartForm::OPTION_SHOPPING_LISTS
     */
    protected const OPTION_SHOPPING_LISTS = 'OPTION_SHOPPING_LISTS';

    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface $shoppingListClient
     */
    public function __construct(ShoppingListPageToShoppingListClientInterface $shoppingListClient)
    {
        $this->shoppingListClient = $shoppingListClient;
    }

    /**
     * @param int|null $idQuote
     *
     * @return \Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer
     */
    public function getData(?int $idQuote): ShoppingListFromCartRequestTransfer
    {
        return (new ShoppingListFromCartRequestTransfer())->setIdQuote($idQuote)->setShoppingListName(null);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => ShoppingListFromCartRequestTransfer::class,
            static::OPTION_SHOPPING_LISTS => $this->getShoppingListCollection(),
        ];
    }

    /**
     * @return array
     */
    protected function getShoppingListCollection(): array
    {
        $shoppingListCollection = [];
        $shoppingListTransferCollection = $this->shoppingListClient->getCustomerShoppingListCollection()->getShoppingLists();

        $shoppingListCollection[self::GLOSSARY_KEY_CART_ADD_TO_SHOPPING_LIST_FORM_ADD_NEW] = '';
        foreach ($shoppingListTransferCollection as $shoppingListTransfer) {
            if ($this->can('WriteShoppingListPermissionPlugin', $shoppingListTransfer->getIdShoppingList())) {
                $shoppingListCollection[$this->generateShoppingListName($shoppingListTransfer)] = $shoppingListTransfer->getIdShoppingList();
            }
        }

        return $shoppingListCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return string
     */
    protected function generateShoppingListName(ShoppingListTransfer $shoppingListTransfer): string
    {
        return "{$shoppingListTransfer->getName()} ({$shoppingListTransfer->getOwner()})";
    }
}
