<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface;
use SprykerShop\Yves\ProductOptionWidget\Mapper\ProductAbstractOptionStorageMapperInterface;

class ShoppingListItemProductOptionFormDataProvider implements ShoppingListItemProductOptionFormDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface
     */
    protected $productOptionStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductOptionWidget\Mapper\ProductAbstractOptionStorageMapperInterface
     */
    protected $productAbstractOptionStorageMapper;

    /**
     * @param \SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface $productOptionStorageClient
     * @param \SprykerShop\Yves\ProductOptionWidget\Mapper\ProductAbstractOptionStorageMapperInterface $productAbstractOptionStorageMapper
     */
    public function __construct(
        ProductOptionWidgetToProductOptionStorageClientInterface $productOptionStorageClient,
        ProductAbstractOptionStorageMapperInterface $productAbstractOptionStorageMapper
    ) {
        $this->productOptionStorageClient = $productOptionStorageClient;
        $this->productAbstractOptionStorageMapper = $productAbstractOptionStorageMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionGroupStorageTransfer[]
     */
    public function findProductOptionGroupsByShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ArrayObject
    {
        $productAbstractOptionStorageTransfer = $this->productOptionStorageClient
            ->getProductOptionsForCurrentStore($shoppingListItemTransfer->getIdProductAbstract());

        if (!$productAbstractOptionStorageTransfer) {
            return new ArrayObject();
        }

        $storageProductOptionGroupCollectionTransfer = $this->productAbstractOptionStorageMapper
            ->mapShoppingListItemProductOptionsToProductAbstractOptionStorage(
                $productAbstractOptionStorageTransfer,
                $shoppingListItemTransfer
            );

        return $storageProductOptionGroupCollectionTransfer->getProductOptionGroups();
    }
}
