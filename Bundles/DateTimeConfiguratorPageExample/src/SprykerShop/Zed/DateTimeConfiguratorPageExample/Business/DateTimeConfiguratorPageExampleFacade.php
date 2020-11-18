<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Zed\DateTimeConfiguratorPageExample\Business;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerShop\Zed\DateTimeConfiguratorPageExample\Business\DateTimeConfiguratorPageExampleBusinessFactory getFactory()
 */
class DateTimeConfiguratorPageExampleFacade extends AbstractFacade implements DateTimeConfiguratorPageExampleFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(
        string $sku,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer = null
    ): ?ProductConcreteAvailabilityTransfer {
        return $this->getFactory()
            ->createProductConcreteAvailabilityReader()
            ->findProductConcreteAvailabilityForStore($sku, $storeTransfer, $productAvailabilityCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function buildProductConfigurationFrontend(LoggerInterface $logger): bool
    {
        return $this->getFactory()
            ->createProductConfiguratorFrontendBuilder()
            ->build($logger);
    }
}
