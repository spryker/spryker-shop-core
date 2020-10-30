<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Zed\DateTimeConfiguratorPageExample\Communication\Plugin\SalesProductConfigurationGui;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesProductConfigurationTemplateTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin\ProductConfigurationRenderStrategyPluginInterface;
use SprykerShop\Shared\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleConfig;

/**
 * @method \SprykerShop\Zed\DateTimeConfiguratorPageExample\Business\DateTimeConfiguratorPageExampleFacade getFacade()
 * @method \SprykerShop\Zed\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleConfig getConfig()
 */
class DateTimeProductConfigurationRenderStrategyPlugin extends AbstractPlugin implements ProductConfigurationRenderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Applicable to items which have a configuration with corresponds key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getSalesOrderItemConfiguration()
            && $itemTransfer->getSalesOrderItemConfiguration()->getConfiguratorKey()
            === DateTimeConfiguratorPageExampleConfig::DATE_TIME_CONFIGURATOR_KEY;
    }

    /**
     * {@inheritDoc}
     * - Decodes json configuration data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesProductConfigurationTemplateTransfer
     */
    public function getTemplate(ItemTransfer $itemTransfer): SalesProductConfigurationTemplateTransfer
    {
        return (new SalesProductConfigurationTemplateTransfer())
            ->setData(json_decode($itemTransfer->getSalesOrderItemConfiguration()->getDisplayData(), true) ?? [])
            ->setTemplatePath('@DateTimeConfiguratorPageExample/_partials/order-item-configuration.twig');
    }
}
