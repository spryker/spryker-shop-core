<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Csv;

use SprykerShop\Shared\QuickOrderPage\QuickOrderPageConfig;
use SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileValidator implements FileValidatorInterface
{
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
     * @param int $maxAllowedLines
     *
     * @return bool
     */
    public function validateAmountOfRows(UploadedFile $file, int $maxAllowedLines): bool
    {
        $uploadedOrder = $this->utilCsvService->readUploadedFile($file);

        if (count($uploadedOrder) === 1) {
            return false;
        }

        if (count($uploadedOrder) > $maxAllowedLines) {
            return false;
        }

        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function validateFormat(UploadedFile $file): bool
    {
        $uploadedOrder = $this->utilCsvService->readUploadedFile($file);

        if (in_array(QuickOrderPageConfig::CSV_SKU_COLUMN_NAME, $uploadedOrder[0])
            && in_array(QuickOrderPageConfig::CSV_QTY_COLUMN_NAME, $uploadedOrder[0])) {
            return true;
        }

        return false;
    }
}
