<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\DateTimeConfiguratorPageExample\Plugin\ProductConfigurationShoppingListWidget;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\DateTimeConfiguratorPageExample\DateTimeConfiguratorPageExampleConfig;
use SprykerShop\Yves\ProductConfigurationShoppingListWidgetExtension\Dependency\Plugin\ShoppingListItemProductConfigurationRenderStrategyPluginInterface;

class ExampleDateTimeShoppingListItemProductConfigurationRenderStrategyPlugin extends AbstractPlugin implements ShoppingListItemProductConfigurationRenderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Applicable to items that have a configuration with a corresponding key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstance
     *
     * @return bool
     */
    public function isApplicable(ProductConfigurationInstanceTransfer $productConfigurationInstance): bool
    {
        return $productConfigurationInstance->getConfiguratorKey()
            === DateTimeConfiguratorPageExampleConfig::DATE_TIME_CONFIGURATOR_KEY;
    }

    /**
     * {@inheritDoc}
     * - Decodes JSON configuration data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstance
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function getTemplate(ProductConfigurationInstanceTransfer $productConfigurationInstance): ProductConfigurationTemplateTransfer
    {
        return (new ProductConfigurationTemplateTransfer())
            ->setData(json_decode($productConfigurationInstance->getDisplayDataOrFail(), true) ?? [])
            ->setModuleName('DateTimeConfiguratorPageExample')
            ->setTemplateType('view')
            ->setTemplateName('options-list');
    }
}
