<?php
/**
 * Created by PhpStorm.
 * User: karolygerner
 * Date: 22.October.2018
 * Time: 14:05
 */

namespace SprykerShop\Yves\QuickOrderPage\PriceResolver;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToPriceProductStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolverInterface;

class PriceResolver implements PriceResolverInterface
{
    /**
     * @var ProductResolverInterface
     */
    protected $productResolver;

    /**
     * @var QuickOrderPageToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @param ProductResolverInterface $productResolver
     * @param QuickOrderPageToPriceProductStorageClientInterface $priceProductStorageClient
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
        $items = [];
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            $items[] = $this->setSumPriceForQuickOrderItemTransfer($quickOrderItemTransfer);
        }

        return $quickOrderTransfer->setItems(new \ArrayObject($items));
    }


    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return QuickOrderItemTransfer
     */
    public function setSumPriceForQuickOrderItemTransfer(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        if (empty($quickOrderItemTransfer->getSku()) || empty($quickOrderItemTransfer->getQuantity())) {
            return $quickOrderItemTransfer;
        }

        $idProduct = $this->productResolver->getIdProductBySku($quickOrderItemTransfer->getSku());

        $sumPrice = $this->getSumPriceForQuantity(
            (int)$quickOrderItemTransfer->getQuantity(),
            $idProduct,
            $this->productResolver->getIdProductAbstractByIdProduct($idProduct)
        );

        $quickOrderItemTransfer->setSumPrice($sumPrice);

        return $quickOrderItemTransfer;
    }

    /**
     * @param int $quantity
     * @param int $idProduct
     * @param int $idProductAbstract
     *
     * @return int
     */
    protected function getSumPriceForQuantity(int $quantity, int $idProduct, int $idProductAbstract): int
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($quantity)
            ->setIdProduct($idProduct)
            ->setIdProductAbstract($idProductAbstract);

        return $this->priceProductStorageClient
            ->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer)
            ->getSumPrice();
    }
}
