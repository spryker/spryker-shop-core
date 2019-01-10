<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\FileOutputter;

class FileOutputter implements FileOutputterInterface
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
     * @param string $fileType
     *
     * @return void
     */
    public function outputFile(string $fileType): void
    {
        foreach ($this->quickOrderFileTemplatePlugins as $fileTemplatePlugin) {
            if ($fileTemplatePlugin->isApplicable($fileType)) {
                $fileName = 'template.' . $fileTemplatePlugin->getFileExtension();
                $fileContent = $fileTemplatePlugin->generateTemplate();
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                header('Content-Type: ' . $fileTemplatePlugin->getTemplateMimeType());
                header('Content-Length: ' . strlen($fileContent));
                header('Connection: close');

                echo $fileContent;
                exit;
            }
        }
    }
}
