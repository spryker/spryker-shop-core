<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\ViewCollector;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductQuantityStorageClientInterface;
use Symfony\Component\Form\FormInterface;

class ProductAdditionalDataViewCollector implements ProductAdditionalDataViewCollectorInterface
{
    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductQuantityStorageClientInterface
     */
    protected $productQuantityStorageClient;

    /**
     * @param \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductQuantityStorageClientInterface $productQuantityStorageClient
     */
    public function __construct(
        ProductSearchWidgetToProductQuantityStorageClientInterface $productQuantityStorageClient
    ) {
        $this->productQuantityStorageClient = $productQuantityStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array
     */
    public function collectViewProductAdditionalData(ProductConcreteTransfer $productConcreteTransfer, FormInterface $form): array
    {
        return [
            'minQuantity' => $productConcreteTransfer->getMinQuantity() ?? 1,
            'maxQuantity' => $productConcreteTransfer->getMaxQuantity(),
            'quantityInterval' => $productConcreteTransfer->getQuantityInterval() ?? 1,
            'form' => $form->createView(),
            'isDisabled' => false,
        ];
    }
}
