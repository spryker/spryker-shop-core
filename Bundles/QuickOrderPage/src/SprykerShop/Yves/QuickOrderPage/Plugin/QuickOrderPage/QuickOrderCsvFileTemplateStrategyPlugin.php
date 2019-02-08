<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplateStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderCsvFileTemplateStrategyPlugin extends AbstractPlugin implements QuickOrderFileTemplateStrategyPluginInterface
{
    public const CSV_SKU_COLUMN_NAME = 'concrete_sku';
    public const CSV_QUANTITY_COLUMN_NAME = 'quantity';
    public const CSV_COLUMN_SEPARATOR = ',';
    public const CSV_FILE_MIME_TYPE = 'text/csv';
    public const CSV_FILE_EXTENSION = 'csv';

    /**
     * {@inheritdoc}
     * - Returns true if provided file extension matches the expected file extension.
     *
     * @api
     *
     * @param string $fileExtension
     *
     * @return bool
     */
    public function isApplicable(string $fileExtension): bool
    {
        return $fileExtension === $this->getFileExtension();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        return static::CSV_FILE_EXTENSION;
    }

    /**
     * {@inheritdoc}
     * - Returns template with header row and example rows.
     *
     * @api
     *
     * @return string
     */
    public function generateTemplate(): string
    {
        return $this->getExampleTemplateHeader() . $this->getExampleTemplateBody();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getTemplateMimeType(): string
    {
        return static::CSV_FILE_MIME_TYPE;
    }

    /**
     * @return string
     */
    protected function getExampleTemplateHeader(): string
    {
        return static::CSV_SKU_COLUMN_NAME
            . static::CSV_COLUMN_SEPARATOR
            . static::CSV_QUANTITY_COLUMN_NAME
            . PHP_EOL;
    }

    /**
     * @return string
     */
    protected function getExampleTemplateBody(): string
    {
        return 'example_sku_1' . static::CSV_COLUMN_SEPARATOR . '1' . PHP_EOL
            . 'example_sku_2' . static::CSV_COLUMN_SEPARATOR . '2';
    }
}
