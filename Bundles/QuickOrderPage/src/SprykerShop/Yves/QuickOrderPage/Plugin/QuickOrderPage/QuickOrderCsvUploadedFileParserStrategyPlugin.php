<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderUploadedFileParserStrategyPluginInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderCsvUploadedFileParserStrategyPlugin extends AbstractPlugin implements QuickOrderUploadedFileParserStrategyPluginInterface
{
    protected const CSV_FILE_MIME_TYPES = [
        'text/csv',
        'text/plain',
        'text/x-csv',
        'application/vnd.ms-excel',
        'application/csv',
        'application/x-csv',
        'text/comma-separated-values',
        'text/x-comma-separated-values',
        'text/tab-separated-values',
        'application/octet-stream',
    ];

    /**
     * {@inheritDoc}
     * - Returns true if the provided mime type matches the expected mime type.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isApplicable(UploadedFile $file): bool
    {
        return in_array($file->getClientMimeType(), static::CSV_FILE_MIME_TYPES, true);
    }

    /**
     * {@inheritDoc}
     * - Expects first row to contain the header.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parseFile(UploadedFile $file): array
    {
        return $this->getFactory()
            ->createUploadedFileCsvTypeParser()
            ->parse($file);
    }
}
