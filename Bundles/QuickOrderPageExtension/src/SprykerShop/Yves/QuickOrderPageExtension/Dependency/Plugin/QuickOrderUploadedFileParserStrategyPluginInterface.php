<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface QuickOrderUploadedFileParserStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable to work with provided file.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isApplicable(UploadedFile $file): bool;

    /**
     * Specification:
     * - Parses provided file and returns array of QuickOrderItemTransfers.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parseFile(UploadedFile $file): array;
}
