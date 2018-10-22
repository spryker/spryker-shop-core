<?php

namespace SprykerShop\Yves\QuickOrderPage\ProductResolver;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;

interface ProductResolverInterface
{
    /**
     * @param string $sku
     *
     * @return int
     */
    public function getIdProductBySku(string $sku): int;

    /**
     * @param string $sku
     *
     * @return ProductConcreteTransfer
     */
    public function getProductBySku(string $sku): ProductConcreteTransfer;

    /**
     * @param int $idProduct
     *
     * @return int
     */
    public function getIdProductAbstractByIdProduct(int $idProduct): int;

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return ProductConcreteTransfer[] Keys are product SKUs
     */
    public function getProductsByQuickOrder(QuickOrderTransfer $quickOrderTransfer): array;
}
