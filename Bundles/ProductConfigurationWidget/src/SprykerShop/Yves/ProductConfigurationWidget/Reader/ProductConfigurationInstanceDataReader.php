<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Reader;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

class ProductConfigurationInstanceDataReader implements ProductConfigurationInstanceDataReaderInterface
{
    protected const TEMPLATE_PATH_KEY = 'path';
    protected const TEMPLATE_DATA_KEY = 'data';

    /**
     * @var array
     */
    protected $productConfigurationRendererPlugins;

    /**
     * @param \SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRendererPluginInterface[] $productConfigurationRendererPlugins
     */
    public function __construct(array $productConfigurationRendererPlugins)
    {
        $this->productConfigurationRendererPlugins = $productConfigurationRendererPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return array
     */
    public function getProductConfigurationInstanceTemplateData(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): array {
        foreach ($this->productConfigurationRendererPlugins as $plugin) {
            if ($plugin->isApplicable($productConfigurationInstanceTransfer)) {
                return [
                    static::TEMPLATE_PATH_KEY => $plugin->getTemplatePath(),
                    static::TEMPLATE_DATA_KEY => $plugin->getTemplateData($productConfigurationInstanceTransfer),
                ];
            }
        }
    }
}
