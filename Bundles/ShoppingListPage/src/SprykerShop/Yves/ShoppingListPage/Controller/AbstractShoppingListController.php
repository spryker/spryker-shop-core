<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class AbstractShoppingListController extends AbstractController
{
    protected const ROUTE_PARAM_ID_SHOPPING_LIST = 'idShoppingList';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $customerTransfer = $this->getCustomer();

        if ($customerTransfer === null || !$customerTransfer->getCompanyUserTransfer()) {
            throw new NotFoundHttpException('Only company users are allowed to access this page');
        }
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomer(): ?CustomerTransfer
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function createProductView(ShoppingListItemTransfer $shoppingListItemTransfer): ProductViewTransfer
    {
        $productConcreteStorageData = $this->getFactory()
            ->getProductStorageClient()
            ->findProductConcreteStorageData($shoppingListItemTransfer->getIdProduct(), $this->getLocale());

        $productViewTransfer = new ProductViewTransfer();
        if (empty($productConcreteStorageData)) {
            $productConcreteStorageData = [
                ProductViewTransfer::SKU => $shoppingListItemTransfer->getSku(),
            ];
        }
        $productViewTransfer->fromArray($productConcreteStorageData, true);

        $productViewTransfer->setQuantity($shoppingListItemTransfer->getQuantity());
        $productViewTransfer->setShoppingListItem($shoppingListItemTransfer);
        $productViewTransfer->setIdShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        foreach ($this->getFactory()->getShoppingListItemExpanderPlugins() as $productViewExpanderPlugin) {
            $productViewExpanderPlugin->expandProductViewTransfer(
                $productViewTransfer,
                $productConcreteStorageData,
                $this->getLocale()
            );
        }

        return $productViewTransfer;
    }
}
