<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 5/2/18
 * Time: 15:26
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\CustomerReorderWidget\CustomerReorderWidgetPluginInterface;

class CustomerReorderWidgetPlugin extends AbstractWidgetPlugin implements CustomerReorderWidgetPluginInterface
{
    /**
     * @param OrderTransfer $orderTransfer
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer): void
    {
        $this->addParameter('order', $orderTransfer);
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
        return '@CustomerReorderWidget/_customer-page/reorder-button.twig';
    }
}
