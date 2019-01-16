<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\TemplateExtensionsReader;

class TemplateExtensionsReader implements TemplateExtensionsReaderInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplatePluginInterface[]
     */
    protected $quickOrderFileTemplatePlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplatePluginInterface[] $quickOrderFileTemplatePlugins
     */
    public function __construct(array $quickOrderFileTemplatePlugins)
    {
        $this->quickOrderFileTemplatePlugins = $quickOrderFileTemplatePlugins;
    }

    /**
     * @return string[]
     */
    public function getTemplateExtensions(): array
    {
        $templateExtensions = [];
        foreach ($this->quickOrderFileTemplatePlugins as $fileTemplatePlugin) {
            $templateExtensions[$fileTemplatePlugin->getFileExtension()] = $fileTemplatePlugin->getFileExtension();
        }

        return $templateExtensions;
    }
}
