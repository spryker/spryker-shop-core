<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use Generated\Shared\Transfer\ShoppingListDismissRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShoppingListDismissController extends AbstractShoppingListController
{
    protected const GLOSSARY_KEY_SHOPPING_LIST_PAGE_DISMISS_SUCCESS = 'shopping_list_page.dismiss.success';
    protected const GLOSSARY_KEY_SHOPPING_LIST_PAGE_DISMISS_FAILED = 'shopping_list_page.dismiss.failed';

    /**
     * @uses \SprykerShop\Yves\ShoppingListPage\Plugin\Router\ShoppingListPageRouteProviderPlugin::ROUTE_SHOPPING_LIST
     */
    protected const ROUTE_SHOPPING_LIST = 'shopping-list';

    /**
     * @param int $idShoppingList
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function dismissAction(int $idShoppingList): RedirectResponse
    {
        $customerTransfer = $this->getCustomer();
        $shoppingListDismissRequest = (new ShoppingListDismissRequestTransfer())
            ->setIdShoppingList($idShoppingList)
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());
        $shoppingListShareResponseTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->dismissShoppingListSharing($shoppingListDismissRequest);

        if ($shoppingListShareResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_SHOPPING_LIST_PAGE_DISMISS_SUCCESS);

            return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST);
        }

        $this->addErrorMessage(static::GLOSSARY_KEY_SHOPPING_LIST_PAGE_DISMISS_FAILED);

        return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST);
    }

    /**
     * @param int $idShoppingList
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function dismissConfirmAction(int $idShoppingList)
    {
        $response = $this->executeDismissConfirmAction($idShoppingList);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@ShoppingListPage/views/shopping-list-dismiss-confirm/shopping-list-dismiss-confirm.twig');
    }

    /**
     * @param int $idShoppingList
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeDismissConfirmAction(int $idShoppingList)
    {
        $customerTransfer = $this->getCustomer();

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($idShoppingList)
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->getShoppingList($shoppingListTransfer);

        if ($shoppingListTransfer->getCustomerReference() === $customerTransfer->getCustomerReference()) {
            return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST);
        }

        return [
            'shoppingList' => $shoppingListTransfer,
        ];
    }
}
