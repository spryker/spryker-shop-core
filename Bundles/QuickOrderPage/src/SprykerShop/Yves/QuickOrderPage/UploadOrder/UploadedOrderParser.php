<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\UploadOrder;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use SprykerShop\Shared\QuickOrderPage\QuickOrderPageConfig as QuickOrderPageConfigShared;
use SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedOrderParser implements UploadedOrderParserInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface
     */
    protected $utilCsvService;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig $config
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface $utilCsvService
     */
    public function __construct(QuickOrderPageConfig $config, QuickOrderPageToUtilCsvServiceInterface $utilCsvService)
    {
        $this->config = $config;
        $this->utilCsvService = $utilCsvService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadOrder
     *
     * @return array
     */
    public function parse(UploadedFile $uploadOrder): array
    {
        $rows = $this->getUploadOrderRows($uploadOrder);

        $csvHeader = array_flip($rows[0]);
        $skuKey = array_search(QuickOrderPageConfigShared::CSV_SKU_COLUMN_NAME, array_keys($csvHeader));
        $qtyKey = array_search(QuickOrderPageConfigShared::CSV_QTY_COLUMN_NAME, array_keys($csvHeader));

        $quickOrderItemTransfers = [];
        unset($rows[0]);
        foreach ($rows as $row) {
            $quickOrderItemTransfers[] = $this->createQuickOrderItemTransfer($row[$skuKey], (int)$row[$qtyKey]);
        }

        return array_values($quickOrderItemTransfers);
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
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadOrder
     *
     * @return array
     */
    protected function getUploadOrderRows(UploadedFile $uploadOrder): array
    {
        return $this->utilCsvService->readUploadedFile($uploadOrder);
    }
}
