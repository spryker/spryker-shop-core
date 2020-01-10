<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\CsvType;

use SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileTypeValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileCsvTypeValidator implements UploadedFileTypeValidatorInterface
{
    protected const CSV_SKU_COLUMN_NAME = 'concrete_sku';
    protected const CSV_QUANTITY_COLUMN_NAME = 'quantity';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface
     */
    protected $utilCsvService;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface $utilCsvService
     */
    public function __construct(QuickOrderPageToUtilCsvServiceInterface $utilCsvService)
    {
        $this->utilCsvService = $utilCsvService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $rowCountLimit
     *
     * @return bool
     */
    public function isValidRowCount(UploadedFile $file, int $rowCountLimit): bool
    {
        $uploadedOrder = $this->utilCsvService->readUploadedFile($file);

        return $rowCountLimit >= count($uploadedOrder);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function validateFormat(UploadedFile $file): bool
    {
        $uploadedOrder = $this->utilCsvService->readUploadedFile($file);

        if (count($uploadedOrder) <= 1) {
            return false;
        }

        if (!in_array(static::CSV_SKU_COLUMN_NAME, $uploadedOrder[0], true)
            || !in_array(static::CSV_QUANTITY_COLUMN_NAME, $uploadedOrder[0], true)
        ) {
            return false;
        }

        return true;
    }
}
