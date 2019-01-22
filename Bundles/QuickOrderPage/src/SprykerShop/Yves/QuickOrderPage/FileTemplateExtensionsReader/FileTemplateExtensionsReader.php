<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\FileTemplateExtensionsReader;

class FileTemplateExtensionsReader implements FileTemplateExtensionsReaderInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplateStrategyPluginInterface[]
     */
    protected $quickOrderFileTemplatePlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplateStrategyPluginInterface[] $quickOrderFileTemplatePlugins
     */
    public function __construct(array $quickOrderFileTemplatePlugins)
    {
        $this->quickOrderFileTemplatePlugins = $quickOrderFileTemplatePlugins;
    }

    /**
     * @return string[]
     */
    public function getFileTemplateExtensions(): array
    {
        $fileTemplateExtensions = [];
        foreach ($this->quickOrderFileTemplatePlugins as $fileTemplatePlugin) {
            $fileTemplateExtensions[$fileTemplatePlugin->getFileExtension()] = $fileTemplatePlugin->getFileExtension();
        }

        return $fileTemplateExtensions;
    }
}
