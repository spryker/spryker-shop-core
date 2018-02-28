<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Validator;

use SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig;

class TextOrderValidator
{
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
     * @return bool
     */
    public function checkFormat(string $textOrder): bool
    {
        $passed = false;

        foreach ($this->config->getAllowedSeparators() as $separator) {
            if (strpos($textOrder, $separator) !== false && $this->checkEachRow($textOrder, $separator)) {
                return true;
            }
        }

        return $passed;
    }

    public function checkTextOrderItems(string $textOrder)
    {
        //todo
        return true;
    }

    /**
     * @param string $textOrder
     * @param string $separator
     *
     * @return bool
     */
    protected function checkEachRow(string $textOrder, string $separator): bool
    {
        $rows = $this->getTextOrderRows($textOrder);
        foreach ($rows as $row) {
            if (strpos($row, $separator) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $textOrder
     *
     * @return array
     */
    protected function getTextOrderRows(string $textOrder)
    {
        return array_filter(preg_split('/\r\n|\r|\n/', $textOrder));
    }
}
