<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\DataProvider;

use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;

class ShoppingListFormDataProvider
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
     * @var \SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListDataProviderExpanderPluginInterface[]
     */
    protected $shoppingListDataProviderExpanderPlugins;

    /**
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface $shoppingListClient
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListDataProviderExpanderPluginInterface[] $shoppingListDataProviderExpanderPlugins
     */
    public function __construct(
        ShoppingListPageToShoppingListClientInterface $shoppingListClient,
        ShoppingListPageToCustomerClientInterface $customerClient,
        array $shoppingListDataProviderExpanderPlugins
    ) {
        $this->shoppingListClient = $shoppingListClient;
        $this->customerClient = $customerClient;
        $this->shoppingListDataProviderExpanderPlugins = $shoppingListDataProviderExpanderPlugins;
    }

    /**
     * @param int $idShoppingList
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getData(int $idShoppingList, array $params): ShoppingListTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        $shoppingListTransfer = new ShoppingListTransfer();
        $shoppingListTransfer
            ->setIdShoppingList($idShoppingList)
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListTransfer = $this->shoppingListClient->getShoppingList($shoppingListTransfer);

        foreach ($this->shoppingListDataProviderExpanderPlugins as $shoppingListDataProviderExpanderPlugin) {
            $shoppingListTransfer = $shoppingListDataProviderExpanderPlugin->expandData($shoppingListTransfer, $params);
        }

        return $shoppingListTransfer;
    }
}
