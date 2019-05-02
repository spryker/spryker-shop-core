<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\ViewCollector;

use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToAvailabilityClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductQuantityStorageClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Reader\QuantityRestrictionReaderInterface;
use Symfony\Component\Form\FormInterface;

class ProductAdditionalDataViewCollector implements ProductAdditionalDataViewCollectorInterface
{
    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Reader\QuantityRestrictionReaderInterface
     */
    protected $quantityRestrictionReader;

    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToAvailabilityClientInterface
     */
    protected $availabilityClient;

    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductQuantityStorageClientInterface
     */
    protected $productQuantityStorageClient;

    /**
     * @param \SprykerShop\Yves\ProductSearchWidget\Reader\QuantityRestrictionReaderInterface $quantityRestrictionReader
     * @param \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToAvailabilityClientInterface $availabilityClient
     * @param \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductQuantityStorageClientInterface $productQuantityStorageClient
     */
    public function __construct(
        QuantityRestrictionReaderInterface $quantityRestrictionReader,
        ProductSearchWidgetToAvailabilityClientInterface $availabilityClient,
        ProductSearchWidgetToProductQuantityStorageClientInterface $productQuantityStorageClient
    ) {
        $this->quantityRestrictionReader = $quantityRestrictionReader;
        $this->availabilityClient = $availabilityClient;
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
        $productQuantityStorageTransfer = $this->productQuantityStorageClient
            ->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());
        $availabilityRequestTransfer = new ProductConcreteAvailabilityRequestTransfer();
        $availabilityRequestTransfer->setSku($productConcreteTransfer->getSku());
        $productConcreteAvailabilityTransfer = $this->availabilityClient
            ->findProductConcreteAvailability($availabilityRequestTransfer);

        $minQuantity = $this->quantityRestrictionReader->getMinQuantity(
            $productQuantityStorageTransfer,
            $productConcreteAvailabilityTransfer
        );
        $maxQuantity = $this->quantityRestrictionReader->getMaxQuantity(
            $productQuantityStorageTransfer,
            $productConcreteAvailabilityTransfer
        );
        $quantityInterval = $this->quantityRestrictionReader->getQuantityInterval($productQuantityStorageTransfer);

        return [
            'minQuantity' => $minQuantity,
            'maxQuantity' => $maxQuantity,
            'quantityInterval' => $quantityInterval,
            'form' => $form->createView(),
            'isDisabled' => $this->isDisabled($minQuantity, $maxQuantity),
        ];
    }

    /**
     * @param float|null $minQuantity
     * @param float|null $maxQuantity
     *
     * @return bool
     */
    public function isDisabled(?float $minQuantity, ?float $maxQuantity): bool
    {
        return $minQuantity > 0 && $maxQuantity > 0 && $maxQuantity > $minQuantity ? false : true;
    }
}
