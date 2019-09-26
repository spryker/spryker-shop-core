<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ShoppingListPage;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListFormDataProviderMapperPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ShoppingListItemProductOptionFormDataProviderMapperPlugin extends AbstractPlugin implements ShoppingListFormDataProviderMapperPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Maps provided parameters into ShoppingLisTransfer to populate product option params.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function map(ShoppingListTransfer $shoppingListTransfer, array $params): ShoppingListTransfer
    {
        return $this->getFactory()
            ->createShoppingListTransferMapper()
            ->mapProductOptionsToShoppingList($shoppingListTransfer, $params);
    }
}
