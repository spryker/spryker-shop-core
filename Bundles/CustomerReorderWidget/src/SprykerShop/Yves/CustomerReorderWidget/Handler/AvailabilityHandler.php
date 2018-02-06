<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 6/2/18
 * Time: 21:39
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;


use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface;

/**
 * @todo discuss
 * this is hard dependency on availability
 */
class AvailabilityHandler
{
    /**
     * @var CustomerReorderWidgetToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param CustomerReorderWidgetToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        CustomerReorderWidgetToProductStorageClientInterface $productStorageClient
    )
    {
        $this->productStorageClient = $productStorageClient;
    }

    public function getAvailabilityForItemTransfer(ItemTransfer $itemTransfer, string $locale): bool
    {
        $productConcreteStorageData = $this->productStorageClient
            ->getProductConcreteStorageData($itemTransfer->getId(), $locale);

        $productViewTransfer = new ProductViewTransfer();
        $productViewTransfer->fromArray($productConcreteStorageData, true);

        if ($productViewTransfer->getPrice() === null) {
            return false;
        }

        return $productViewTransfer->getAvailable();
    }
}
