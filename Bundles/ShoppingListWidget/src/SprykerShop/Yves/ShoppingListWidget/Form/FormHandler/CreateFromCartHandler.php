<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Form\FormHandler;

use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Form\ShoppingListFromCartForm;
use Symfony\Component\Form\FormInterface;

class CreateFromCartHandler implements CreateFromCartHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @var \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface $shoppingListClient
     * @param \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToCustomerClientInterface $customerClient
     */
    public function __construct(
        ShoppingListWidgetToShoppingListClientInterface $shoppingListClient,
        ShoppingListWidgetToCustomerClientInterface $customerClient
    ) {
        $this->shoppingListClient = $shoppingListClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $cartToShoppingListForm
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingListFromCart(FormInterface $cartToShoppingListForm): ShoppingListTransfer
    {
        /** @var \Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer $shoppingListFromCartRequest */
        $shoppingListFromCartRequest = $cartToShoppingListForm->getData();

        if (!$shoppingListFromCartRequest->getIdShoppingList()) {
            $shoppingListFromCartRequest->setShoppingListName(
                $cartToShoppingListForm->get(ShoppingListFromCartForm::FIELD_NEW_SHOPPING_LIST_NAME_INPUT)->getData()
            );
        }
        $shoppingListFromCartRequest->setCustomer($this->customerClient->getCustomer());

        return $this->shoppingListClient->createShoppingListFromQuote($shoppingListFromCartRequest);
    }
}
