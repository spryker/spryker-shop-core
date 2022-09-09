<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\DateTimeConfiguratorPageExample\Plugin\ProductConfiguration;

use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderPluginInterface;

class ExampleDateTimeProductConfiguratorRequestExpanderPlugin extends AbstractPlugin implements ProductConfiguratorRequestExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    public function expand(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer {
        return $productConfiguratorRequestTransfer->setAccessTokenRequestUrl($this->createConfiguratorUrl());
    }

    /**
     * @return string
     */
    protected function createConfiguratorUrl(): string
    {
        return sprintf(
            '%s://%s',
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_PORT') === '443' ? 'https' : 'http',
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_HOST') ?: '',
        );
    }
}
