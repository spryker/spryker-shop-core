<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ShoppingListPage;

use ArrayObject;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListDataProviderExpanderPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ShoppingListItemProductOptionDataProviderExpanderPlugin extends AbstractPlugin implements ShoppingListDataProviderExpanderPluginInterface
{
    protected const ITEMS_FIELD_NAME = 'items';
    protected const PRODUCT_OPTIONS_FIELD_NAME = 'productOptions';

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
        if (!$request->request->has(static::FORM_NAME)) {
            return $shoppingListTransfer;
        }

        $data = $request->request->get(static::FORM_NAME);

        foreach ($shoppingListTransfer->getItems() as $key => $itemTransfer) {
            if ($data[ShoppingListTransfer::ITEMS] && $data[ShoppingListTransfer::ITEMS][$key]) {
                $options = array_filter($data[ShoppingListTransfer::ITEMS][$key][static::PRODUCT_OPTIONS_FIELD_NAME]);
                $productOptions = [];
                foreach ($options as $idProductOptionValue) {
                    $productOptions[] = (new ProductOptionTransfer())->setIdProductOptionValue($idProductOptionValue);
                }
                $itemTransfer->setProductOptions(new ArrayObject($productOptions));
            }
        }

        return $shoppingListTransfer;
    }
}
