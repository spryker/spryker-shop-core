<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\CsvType;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileCsvTypeSanitizerInterface;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileTypeParserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileCsvTypeParser implements UploadedFileTypeParserInterface
{
    protected const CSV_SKU_COLUMN_NAME = 'concrete_sku';
    protected const CSV_QUANTITY_COLUMN_NAME = 'quantity';
    protected const MESSAGE_TYPE_ERROR = 'error';
    protected const ERROR_MESSAGE_QUANTITY_INVALID = 'quick-order.errors.quantity-invalid';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface
     */
    protected $utilCsvService;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileCsvTypeSanitizerInterface
     */
    protected $uploadedFileCsvTypeSanitizer;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface $utilCsvService
     * @param \SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileCsvTypeSanitizerInterface $uploadedFileCsvTypeSanitizer
     */
    public function __construct(
        QuickOrderPageToUtilCsvServiceInterface $utilCsvService,
        UploadedFileCsvTypeSanitizerInterface $uploadedFileCsvTypeSanitizer
    ) {
        $this->utilCsvService = $utilCsvService;
        $this->uploadedFileCsvTypeSanitizer = $uploadedFileCsvTypeSanitizer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parse(UploadedFile $file): array
    {
        $quickOrderItemTransfers = [];
        $rows = $this->getUploadOrderRows($file);

        if (!isset($rows[0][0])) {
            return $quickOrderItemTransfers;
        }

        $csvHeader = array_flip($rows[0]);
        $skuKey = array_search(static::CSV_SKU_COLUMN_NAME, array_keys($csvHeader), true);
        $quantityKey = array_search(static::CSV_QUANTITY_COLUMN_NAME, array_keys($csvHeader), true);

        unset($rows[0]);

        foreach ($rows as $row) {
            if (empty($row[$skuKey]) || !isset($row[$quantityKey])) {
                continue;
            }

            $quickOrderItemTransfer = (new QuickOrderItemTransfer())->setSku($row[$skuKey]);
            $sanitizeQuantity = $this->uploadedFileCsvTypeSanitizer->sanitizeQuantity($row[$quantityKey]);

            if ($sanitizeQuantity != $row[$quantityKey]) {
                $row[$quantityKey] = $sanitizeQuantity;
                $quickOrderItemTransfer->addMessage((new MessageTransfer())
                    ->setType(static::MESSAGE_TYPE_ERROR)
                    ->setValue(static::ERROR_MESSAGE_QUANTITY_INVALID));
            }

            $quickOrderItemTransfer->setQuantity($row[$quantityKey]);
            $quickOrderItemTransfers[] = $quickOrderItemTransfer;
        }

        return $quickOrderItemTransfers;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedOrder
     *
     * @return array
     */
    protected function getUploadOrderRows(UploadedFile $uploadedOrder): array
    {
        return $this->utilCsvService->readUploadedFile($uploadedOrder);
    }
}
