<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ShoppingListPage;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListDataProviderExpanderPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ShoppingListItemProductOptionDataProviderExpanderPlugin extends AbstractPlugin implements ShoppingListDataProviderExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Expands ShoppingListTransfer with product options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function expandData(ShoppingListTransfer $shoppingListTransfer, Request $request): ShoppingListTransfer
    {
        return $this->getFactory()
            ->createShoppingListItemProductOptionFormDataProvider()
            ->expandData($shoppingListTransfer, $request);
    }
}
