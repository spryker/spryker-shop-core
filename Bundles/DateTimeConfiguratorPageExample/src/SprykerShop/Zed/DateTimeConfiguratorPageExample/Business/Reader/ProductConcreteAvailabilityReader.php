<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Zed\DateTimeConfiguratorPageExample\Business\Reader;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerShop\Zed\DateTimeConfiguratorPageExample\Dependency\Facade\DateTimeConfiguratorPageExampleToAvailabilityFacadeInterface;

class ProductConcreteAvailabilityReader implements ProductConcreteAvailabilityReaderInterface
{
    /**
     * @var \SprykerShop\Zed\DateTimeConfiguratorPageExample\Dependency\Facade\DateTimeConfiguratorPageExampleToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \SprykerShop\Zed\DateTimeConfiguratorPageExample\Dependency\Facade\DateTimeConfiguratorPageExampleToAvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct(DateTimeConfiguratorPageExampleToAvailabilityFacadeInterface $availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityForStore(
        string $sku,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): ?ProductConcreteAvailabilityTransfer {
        if (!$productAvailabilityCriteriaTransfer) {
            return null;
        }

        return $this->resolveProductConfigurationAvailability($sku, $storeTransfer, $productAvailabilityCriteriaTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    protected function resolveProductConfigurationAvailability(
        string $sku,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): ?ProductConcreteAvailabilityTransfer {
        $productConfigurationAvailabilityQuantity = $productAvailabilityCriteriaTransfer->getProductConfigurationInstance()
            ->getAvailableQuantity();

        $productConcreteAvailabilityTransfer = $this->availabilityFacade->findOrCreateProductConcreteAvailabilityBySkuForStore(
            $sku,
            $storeTransfer,
            $this->createProductAvailabilityCriteriaCopyWithoutProductConfigurationInstance($productAvailabilityCriteriaTransfer)
        );

        if (!$productConfigurationAvailabilityQuantity && !$productConcreteAvailabilityTransfer) {
            return null;
        }

        if ($productConfigurationAvailabilityQuantity === null && $productConcreteAvailabilityTransfer) {
            return $productConcreteAvailabilityTransfer;
        }

        if ($productConfigurationAvailabilityQuantity !== null && !$productConcreteAvailabilityTransfer) {
            return (new ProductConcreteAvailabilityTransfer())
                ->setAvailability($productConfigurationAvailabilityQuantity)
                ->setSku($sku);
        }

        if ($productConcreteAvailabilityTransfer->getAvailability()->lessThan($productConfigurationAvailabilityQuantity)) {
            return $productConcreteAvailabilityTransfer;
        }

        return $productConcreteAvailabilityTransfer->setAvailability($productConfigurationAvailabilityQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer
     */
    protected function createProductAvailabilityCriteriaCopyWithoutProductConfigurationInstance(
        ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
    ): ProductAvailabilityCriteriaTransfer {
        $productAvailabilityCriteriaTransferCopy = (new ProductAvailabilityCriteriaTransfer())
            ->fromArray($productAvailabilityCriteriaTransfer->toArray());

        return $productAvailabilityCriteriaTransferCopy->setProductConfigurationInstance(null);
    }
}
