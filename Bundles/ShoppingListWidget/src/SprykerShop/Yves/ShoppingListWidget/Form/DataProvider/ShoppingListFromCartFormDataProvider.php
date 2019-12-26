<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Form\DataProvider;

use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Form\ShoppingListFromCartForm;

class ShoppingListFromCartFormDataProvider
{
    use PermissionAwareTrait;

    protected const GLOSSARY_KEY_CART_ADD_TO_SHOPPING_LIST_FORM_ADD_NEW = 'cart.add-to-shopping-list.form.add_new';

    /**
     * @var \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @param \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface $shoppingListClient
     */
    public function __construct(ShoppingListWidgetToShoppingListClientInterface $shoppingListClient)
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
        return (new ShoppingListFromCartRequestTransfer())
            ->setIdQuote($idQuote)
            ->setShoppingListName(null);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => ShoppingListFromCartRequestTransfer::class,
            ShoppingListFromCartForm::OPTION_SHOPPING_LISTS => $this->getShoppingListCollection(),
        ];
    }

    /**
     * @return int[]
     */
    protected function getShoppingListCollection(): array
    {
        $shoppingListCollection = [];
        $shoppingListTransferCollection = $this->shoppingListClient->getCustomerShoppingListCollection()->getShoppingLists();

        $shoppingListCollection[static::GLOSSARY_KEY_CART_ADD_TO_SHOPPING_LIST_FORM_ADD_NEW] = '';
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
        return sprintf('%s (%s)', $shoppingListTransfer->getName(), $shoppingListTransfer->getOwner());
    }
}
