<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class QuickOrderPageConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const TEXT_ORDER_ROW_SPLITTER_PATTERN = '/\r\n|\r|\n/';

    /**
     * @var array
     */
    protected const TEXT_ORDER_SEPARATORS = [',', ';', ' '];

    /**
     * @var int
     */
    protected const UPLOAD_ROW_COUNT_LIMIT = 1000;

    /**
     * @var int
     */
    protected const DEFAULT_DISPLAYED_ROW_COUNT = 8;

    /**
     * @var int
     */
    protected const MAX_ALLOWED_QUANTITY = 100000;

    /**
     * @var array<string>
     */
    protected const ALLOWED_CSV_FILE_MIME_TYPES = [
        'text/csv',
    ];

    /**
     * @var int
     */
    protected const UPLOAD_MAX_FILE_COUNT = 1;

    /**
     * @var string
     */
    protected const UPLOAD_MAX_TOTAL_FILE_SIZE = '5 MB';

    /**
     * @var string
     */
    protected const UPLOAD_DISPLAY_ALLOWED_FILE_TYPES_TEXT = 'csv';

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return int
     */
    public function getUploadMaxFileCount(): int
    {
        return static::UPLOAD_MAX_FILE_COUNT;
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getUploadMaxTotalFileSize(): string
    {
        return static::UPLOAD_MAX_TOTAL_FILE_SIZE;
    }

    /**
     * Specification:
     * - This will be displayed as a help text in the Uploader UI.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getUploadDisplayAllowedFileTypesText(): string
    {
        return static::UPLOAD_DISPLAY_ALLOWED_FILE_TYPES_TEXT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTextOrderRowSplitterPattern(): string
    {
        return static::TEXT_ORDER_ROW_SPLITTER_PATTERN;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getTextOrderSeparators(): array
    {
        return static::TEXT_ORDER_SEPARATORS;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getDefaultDisplayedRowCount(): int
    {
        return static::DEFAULT_DISPLAYED_ROW_COUNT;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getUploadRowCountLimit(): int
    {
        return static::UPLOAD_ROW_COUNT_LIMIT;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMaxAllowedQuantity(): int
    {
        return static::MAX_ALLOWED_QUANTITY;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getAllowedCsvFileMimeTypes(): array
    {
        return static::ALLOWED_CSV_FILE_MIME_TYPES;
    }
}
