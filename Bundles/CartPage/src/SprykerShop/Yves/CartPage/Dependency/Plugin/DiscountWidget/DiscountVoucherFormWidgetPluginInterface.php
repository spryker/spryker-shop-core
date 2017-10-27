<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Plugin\DiscountWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface DiscountVoucherFormWidgetPluginInterface extends WidgetPluginInterface
{

    const NAME = 'DiscountVoucherFormWidgetPlugin';

    /**
     * @return void
     */
    public function initialize(): void;
}
