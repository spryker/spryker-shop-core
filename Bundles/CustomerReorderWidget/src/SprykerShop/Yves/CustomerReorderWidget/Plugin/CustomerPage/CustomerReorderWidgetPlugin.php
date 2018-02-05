<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 5/2/18
 * Time: 15:26
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\CustomerReorderWidget\CustomerReorderWidgetPluginInterface;

class CustomerReorderWidgetPlugin extends AbstractWidgetPlugin implements CustomerReorderWidgetPluginInterface
{
    /**
     * @param OrderTransfer $orderTransfer
     * @param ItemTransfer|null $itemTransfer
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer, ItemTransfer $itemTransfer = null): void
    {
        $this->addParameter('order', $orderTransfer);
        $this->addParameter('item', $itemTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CustomerReorderWidget/_customer-page/index.twig';
    }
}
