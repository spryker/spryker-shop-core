<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\QuickOrderPage;

interface QuickOrderPageConfig
{
    public const CSV_SKU_COLUMN_NAME = 'Identifier';
    public const CSV_QTY_COLUMN_NAME = 'Quantity';
    public const CSV_COLUMN_SEPARATOR = ',';
    public const CSV_FILE_MIME_TYPE = 'text/csv';
    public const CSV_FILE_EXTENSION = 'csv';
}
