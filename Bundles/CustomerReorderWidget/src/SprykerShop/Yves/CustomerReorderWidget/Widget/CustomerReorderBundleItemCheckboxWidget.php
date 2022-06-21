<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Widget;

class CustomerReorderBundleItemCheckboxWidget extends CustomerReorderItemCheckboxWidget
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerReorderBundleItemCheckboxWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CustomerReorderWidget/views/customer-reorder-bundle-item-checkbox/customer-reorder-bundle-item-checkbox.twig';
    }
}
