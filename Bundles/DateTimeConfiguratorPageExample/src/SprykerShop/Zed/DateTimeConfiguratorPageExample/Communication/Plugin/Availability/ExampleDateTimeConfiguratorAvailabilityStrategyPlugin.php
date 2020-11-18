<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Zed\DateTimeConfiguratorPageExample\Communication\Plugin\Availability;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityExtension\Dependency\Plugin\AvailabilityStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use SprykerShop\Shared\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleConfig;

/**
 * @method \SprykerShop\Zed\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleConfig getConfig()
 * @method \SprykerShop\Zed\DateTimeConfiguratorPageExample\Business\DateTimeConfiguratorPageExampleFacadeInterface getFacade()
 */
class ExampleDateTimeConfiguratorAvailabilityStrategyPlugin extends AbstractPlugin implements AvailabilityStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if product configuration instance is exists and has appropriate configurator key.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(
        string $sku,
        StoreTransfer $storeTransfer,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
    ): bool {
        if (!$productAvailabilityCriteriaTransfer) {
            return false;
        }

        $productConfigurationInstanceTransfer = $productAvailabilityCriteriaTransfer->getProductConfigurationInstance();

        return $productConfigurationInstanceTransfer !== null && $productConfigurationInstanceTransfer->getConfiguratorKey()
            === DateTimeConfiguratorPageExampleConfig::DATE_TIME_CONFIGURATOR_KEY;
    }

    /**
     * {@inheritDoc}
     * - Calculates availability for product configuration.
     *
     * @api
     *
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
        return $this->getFacade()->findProductConcreteAvailability(
            $sku,
            $storeTransfer,
            $productAvailabilityCriteriaTransfer
        );
    }
}
