<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\DiscountWidget\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class DiscountWidgetControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_DISCOUNT_VOUCHER_ADD = 'discount/voucher/add';
    const ROUTE_DISCOUNT_VOUCHER_REMOVE = 'discount/voucher/remove';
    const ROUTE_DISCOUNT_VOUCHER_CLEAR = 'discount/voucher/clear';

    const SKU_PATTERN = '[a-zA-Z0-9-_]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{discount}/voucher/add', self::ROUTE_DISCOUNT_VOUCHER_ADD, 'DiscountWidget', 'Voucher', 'add')
            ->assert('discount', $allowedLocalesPattern . 'discount|discount')
            ->value('discount', 'discount');

        $this->createController('/{discount}/voucher/remove', self::ROUTE_DISCOUNT_VOUCHER_REMOVE, 'DiscountWidget', 'Voucher', 'remove')
            ->assert('discount', $allowedLocalesPattern . 'discount|discount')
            ->value('discount', 'discount');

        $this->createController('/{discount}/voucher/clear', self::ROUTE_DISCOUNT_VOUCHER_CLEAR, 'DiscountWidget', 'Voucher', 'clear')
            ->assert('discount', $allowedLocalesPattern . 'discount|discount')
            ->value('discount', 'discount');
    }
}
