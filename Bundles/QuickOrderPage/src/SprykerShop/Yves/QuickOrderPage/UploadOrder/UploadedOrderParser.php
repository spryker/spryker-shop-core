<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\UploadOrder;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedOrderParser implements UploadedOrderParserInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileProcessorStrategyPluginInterface[]
     */
    protected $quickOrderFileProcessorPlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileProcessorStrategyPluginInterface[] $quickOrderFileProcessorPlugins
     */
    public function __construct(array $quickOrderFileProcessorPlugins)
    {
        $this->quickOrderFileProcessorPlugins = $quickOrderFileProcessorPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parseFile(UploadedFile $file): array
    {
        foreach ($this->quickOrderFileProcessorPlugins as $quickOrderFileProcessorPlugin) {
            if ($quickOrderFileProcessorPlugin->isApplicable($file)) {
                return $quickOrderFileProcessorPlugin->parseFile($file);
            }
        }

        return [];
    }
}
