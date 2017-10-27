<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
