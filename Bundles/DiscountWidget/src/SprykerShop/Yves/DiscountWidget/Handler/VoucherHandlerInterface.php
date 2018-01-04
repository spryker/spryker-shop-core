<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Handler;

interface VoucherHandlerInterface
{
    /**
     * @param string $voucherCode
     *
     * @return void
     */
    public function add($voucherCode);

    /**
     * @param string $voucherCode
     *
     * @return void
     */
    public function remove($voucherCode);

    /**
     * @return void
     */
    public function clear();
}
