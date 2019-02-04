<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile;

use SprykerShop\Yves\QuickOrderPage\File\Parser\FileParserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileParser implements FileParserInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileParserStrategyPluginInterface[]
     */
    protected $quickOrderFileParserPlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileParserStrategyPluginInterface[] $quickOrderFileParserPlugins
     */
    public function __construct(array $quickOrderFileParserPlugins)
    {
        $this->quickOrderFileParserPlugins = $quickOrderFileParserPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parse(UploadedFile $file): array
    {
        foreach ($this->quickOrderFileParserPlugins as $quickOrderFileParserPlugin) {
            if ($quickOrderFileParserPlugin->isApplicable($file)) {
                return $quickOrderFileParserPlugin->parseFile($file);
            }
        }

        return [];
    }
}
