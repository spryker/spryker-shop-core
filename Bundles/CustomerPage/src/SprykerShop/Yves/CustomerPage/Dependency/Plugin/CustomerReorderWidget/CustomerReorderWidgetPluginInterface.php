<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 5/2/18
 * Time: 15:22
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin\CustomerReorderWidget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CustomerReorderWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CustomerReorderWidgetPlugin';

    /**
     * @param OrderTransfer $orderTransfer
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer): void;
}
