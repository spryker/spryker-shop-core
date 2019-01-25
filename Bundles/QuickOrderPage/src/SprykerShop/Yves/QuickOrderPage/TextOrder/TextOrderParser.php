<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\TextOrder;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use SprykerShop\Yves\QuickOrderPage\Exception\TextOrderParserException;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig;

class TextOrderParser implements TextOrderParserInterface
{
    protected const ERROR_SEPARATOR_NOT_DETECTED = 'quick-order.paste-order.errors.parser.separator-not-detected';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig $config
     */
    public function __construct(QuickOrderPageConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $textOrder
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function parse(string $textOrder): array
    {
        $rows = $this->getTextOrderRows($textOrder);

        if (count($rows) < 1) {
            return [];
        }

        $separator = $this->detectSeparator($rows);
        $quickOrderItemTransfers = [];
        foreach ($rows as $row) {
            [$sku, $quantity] = explode($separator, trim($row));
            $quantity = (int)$quantity;

            if (empty($sku)) {
                continue;
            }

            $quickOrderItemTransfers = $this->addQuickOrderItemTransfer($quickOrderItemTransfers, $sku, $quantity);
        }

        return array_values($quickOrderItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItemTransfers
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected function addQuickOrderItemTransfer(array $quickOrderItemTransfers, string $sku, int $quantity): array
    {
        if (!isset($quickOrderItemTransfers[$sku])) {
            $quickOrderItemTransfers[$sku] = $this->createQuickOrderItemTransfer($sku, 0);
        }

        $quickOrderItemTransfers[$sku]->setQuantity($quantity + $quickOrderItemTransfers[$sku]->getQuantity());

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
     * @param string $textOrder
     *
     * @return string[]
     */
    protected function getTextOrderRows(string $textOrder): array
    {
        return array_filter(preg_split($this->config->getTextOrderRowSplitterPattern(), $textOrder));
    }

    /**
     * @param string[] $rows
     *
     * @throws \SprykerShop\Yves\QuickOrderPage\Exception\TextOrderParserException
     *
     * @return string
     */
    protected function detectSeparator(array $rows): string
    {
        foreach ($this->config->getTextOrderSeparators() as $separator) {
            foreach ($rows as $row) {
                if (strpos($row, $separator) !== false) {
                    return $separator;
                }
            }
        }

        throw new TextOrderParserException(static::ERROR_SEPARATOR_NOT_DETECTED);
    }
}
