<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Business;

use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListFromCartForm;
use Symfony\Component\Form\FormInterface;

class CreateFromCartHandler implements CreateFromCartHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface $shoppingListClient
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface $customerClient
     */
    public function __construct(
        ShoppingListPageToShoppingListClientInterface $shoppingListClient,
        ShoppingListPageToCustomerClientInterface $customerClient
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
        $shoppingListFromCartRequest = $cartToShoppingListForm->getData();
        if (!$shoppingListFromCartRequest->getShoppingListName()) {
            $shoppingListFromCartRequest->setShoppingListName(
                $cartToShoppingListForm->get(ShoppingListFromCartForm::FIELD_NEW_SHOPPING_LIST_NAME_INPUT)->getData()
            );
        }
        $shoppingListFromCartRequest->setCustomer($this->customerClient->getCustomer());

        return $this->shoppingListClient
            ->createShoppingListFromQuote($shoppingListFromCartRequest);
    }
}
