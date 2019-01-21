<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Csv;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use SprykerShop\Shared\QuickOrderPage\QuickOrderPageConfig as QuickOrderPageConfigShared;
use SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileParser implements FileParserInterface
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
        $skuKey = array_search(QuickOrderPageConfigShared::CSV_SKU_COLUMN_NAME, array_keys($csvHeader));
        $qtyKey = array_search(QuickOrderPageConfigShared::CSV_QTY_COLUMN_NAME, array_keys($csvHeader));

        unset($rows[0]);
        foreach ($rows as $row) {
            $quickOrderItemTransfers[] = $this->createQuickOrderItemTransfer($row[$skuKey], (int)$row[$qtyKey]);
        }

        return $quickOrderItemTransfers;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    protected function createQuickOrderItemTransfer(string $sku, int $quantity): QuickOrderItemTransfer
    {
        $quickOrderItemTransfer = (new QuickOrderItemTransfer())
            ->setSku($sku)
            ->setQuantity($quantity);

        return $quickOrderItemTransfer;
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
