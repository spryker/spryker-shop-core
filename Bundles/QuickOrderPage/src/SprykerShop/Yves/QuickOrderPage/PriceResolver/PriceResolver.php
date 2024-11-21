<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\PriceResolver;

use ArrayObject;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToPriceProductStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolverInterface;

class PriceResolver implements PriceResolverInterface
{
    use PermissionAwareTrait;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolverInterface
     */
    protected $productResolver;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolverInterface $productResolver
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToPriceProductStorageClientInterface $priceProductStorageClient
     */
    public function __construct(ProductResolverInterface $productResolver, QuickOrderPageToPriceProductStorageClientInterface $priceProductStorageClient)
    {
        $this->productResolver = $productResolver;
        $this->priceProductStorageClient = $priceProductStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function setSumPriceForQuickOrderTransfer(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        if (!$this->can('SeePricePermissionPlugin')) {
            return $quickOrderTransfer;
        }

        $items = [];
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            $items[] = $this->setSumPriceForQuickOrderItemTransfer($quickOrderItemTransfer);
        }

        return $quickOrderTransfer->setItems(new ArrayObject($items));
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function setSumPriceForQuickOrderItemTransfer(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        if (!$this->can('SeePricePermissionPlugin') || !$quickOrderItemTransfer->getProductConcrete() || !$quickOrderItemTransfer->getQuantity()) {
            return $quickOrderItemTransfer;
        }

        $idProduct = $quickOrderItemTransfer->getProductConcrete()->getIdProductConcrete();

        if ($idProduct === null) {
            return $quickOrderItemTransfer;
        }

        $currentProductPriceTransfer = $this->getCurrentProductPriceTransfer(
            $quickOrderItemTransfer,
            $idProduct,
            $this->productResolver->getIdProductAbstractByIdProduct($idProduct),
        );

        $quickOrderItemTransfer->setSumPrice($currentProductPriceTransfer->getSumPrice());
        $quickOrderItemTransfer->getProductConcrete()->setDefaultPrice(
            $currentProductPriceTransfer->getPrice() ? (string)$currentProductPriceTransfer->getPrice() : null,
        );
        $quickOrderItemTransfer->getProductConcrete()->setPrices(new ArrayObject($currentProductPriceTransfer->getPrices()));

        return $quickOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param int $idProduct
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    protected function getCurrentProductPriceTransfer(
        QuickOrderItemTransfer $quickOrderItemTransfer,
        int $idProduct,
        int $idProductAbstract
    ): CurrentProductPriceTransfer {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->fromArray($quickOrderItemTransfer->toArray(), true)
            ->setIdProduct($idProduct)
            ->setIdProductAbstract($idProductAbstract)
            ->setSku(null);

        return $this->priceProductStorageClient->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer);
    }
}
