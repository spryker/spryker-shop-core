<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\QuickOrderPage\QuickOrderPageConfig;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplateStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderCsvFileTemplateStrategyPlugin extends AbstractPlugin implements QuickOrderFileTemplateStrategyPluginInterface
{
    /**
     * @param string $fileExtension
     *
     * @return bool
     */
    public function isApplicable(string $fileExtension): bool
    {
        return $fileExtension == $this->getFileExtension();
    }

    /**
     * @return string
     */
    public function getFileExtension(): string
    {
        return QuickOrderPageConfig::CSV_FILE_EXTENSION;
    }

    /**
     * @return string
     */
    public function generateTemplate(): string
    {
        return QuickOrderPageConfig::CSV_SKU_COLUMN_NAME
               . QuickOrderPageConfig::CSV_COLUMN_SEPARATOR
               . QuickOrderPageConfig::CSV_QTY_COLUMN_NAME;
    }

    /**
     * @return string
     */
    public function getTemplateMimeType(): string
    {
        return QuickOrderPageConfig::CSV_FILE_MIME_TYPE;
    }
}
