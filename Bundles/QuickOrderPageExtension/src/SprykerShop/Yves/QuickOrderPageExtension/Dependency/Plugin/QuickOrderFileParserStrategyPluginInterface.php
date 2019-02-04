<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface QuickOrderFileParserStrategyPluginInterface
{
    /**
     * Specification:
     * - Check if file applicable or not.
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
     * - Parse file and return array of QuickOrderItemTransfers.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parseFile(UploadedFile $file): array;
}
