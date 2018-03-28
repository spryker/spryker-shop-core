<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use SprykerShop\Yves\QuickOrderPage\Exception\TextOrderParserException;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig;

class TextOrderParser implements TextOrderParserInterface
{
    public const ERROR_SEPARATOR_NOT_DETECTED = 'quick-order.paste-order.errors.parser.separator-not-detected';

    protected const ROWS_SPLITTER_PATTERN = '/\r\n|\r|\n/';

    /**
     * @var string
     */
    protected $textOrder;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @var \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    protected $parsedTextOrderItems = [];

    /**
     * @var array
     */
    protected $notFoundProducts = [];

    /**
     * @param string $textOrder
     * @param \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig $config
     */
    public function __construct(string $textOrder, QuickOrderPageConfig $config)
    {
        $this->textOrder = $textOrder;
        $this->config = $config;
    }

    /**
     * @return $this
     */
    public function parse(): TextOrderParserInterface
    {
        $rows = $this->getTextOrderRows();

        if (count($rows) > 0) {
            $separator = $this->detectSeparator($rows);
            foreach ($rows as $row) {
                [$skuProductConcrete, $quantity] = explode($separator, trim($row));
                $quantity = (int)$quantity;

                if (empty($skuProductConcrete)) {
                    continue;
                }

                $this->addParsedItem($skuProductConcrete, $quantity);
            }
        }

        return $this;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return void
     */
    protected function addParsedItem(string $sku, int $quantity): void
    {
        if (isset($this->parsedTextOrderItems[$sku])) {
            $quickOrderItemTransfer = $this->parsedTextOrderItems[$sku];
            $quickOrderItemTransfer->setQty($quickOrderItemTransfer->getQty() + $quantity);

            return;
        }

        $this->parsedTextOrderItems[$sku] = $this->getOrderItem($sku, $quantity);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    protected function getOrderItem(string $sku, int $quantity): QuickOrderItemTransfer
    {
        $quickOrderItemTransfer = new QuickOrderItemTransfer();
        $quickOrderItemTransfer->setSku($sku);
        $quickOrderItemTransfer->setQty($quantity ?: 1);

        return $quickOrderItemTransfer;
    }

    /**
     * @return array
     */
    protected function getTextOrderRows(): array
    {
        return array_filter(preg_split(static::ROWS_SPLITTER_PATTERN, $this->textOrder));
    }

    /**
     * @param array $rows
     *
     * @throws \SprykerShop\Yves\QuickOrderPage\Exception\TextOrderParserException
     *
     * @return string
     */
    protected function detectSeparator(array $rows): string
    {
        foreach ($this->config->getAllowedSeparators() as $separator) {
            foreach ($rows as $row) {
                if (strpos($row, $separator) !== false) {
                    return $separator;
                }
            }
        }

        throw new TextOrderParserException(static::ERROR_SEPARATOR_NOT_DETECTED);
    }

    /**
     * @return array
     */
    public function getNotFoundProducts(): array
    {
        return array_values($this->notFoundProducts);
    }

    /**
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer[]
     */
    public function getParsedTextOrderItems(): array
    {
        return array_values($this->parsedTextOrderItems);
    }
}
