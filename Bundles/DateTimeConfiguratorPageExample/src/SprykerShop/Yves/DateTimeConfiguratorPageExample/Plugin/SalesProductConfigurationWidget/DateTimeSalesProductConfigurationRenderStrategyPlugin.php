<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\DateTimeConfiguratorPageExample\Plugin\SalesProductConfigurationWidget;

use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleConfig;
use SprykerShop\Yves\SalesProductConfigurationWidgetExtension\Dependency\Plugin\SalesProductConfigurationRenderStrategyPluginInterface;

class DateTimeSalesProductConfigurationRenderStrategyPlugin extends AbstractPlugin implements SalesProductConfigurationRenderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Applicable to items which have a configuration with corresponds key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return bool
     */
    public function isApplicable(SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer): bool
    {
        return $salesOrderItemConfigurationTransfer->getConfiguratorKey() === DateTimeConfiguratorPageExampleConfig::DATE_TIME_CONFIGURATOR_KEY;
    }

    /**
     * {@inheritDoc}
     * - Decodes json configuration data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function getTemplate(SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer): ProductConfigurationTemplateTransfer
    {
        return (new ProductConfigurationTemplateTransfer())
            ->setData(json_decode($salesOrderItemConfigurationTransfer->getDisplayData(), true) ?? [])
            ->setModuleName('DateTimeConfiguratorPageExample')
            ->setTemplateType('view')
            ->setTemplateName('options-list');
    }
}
