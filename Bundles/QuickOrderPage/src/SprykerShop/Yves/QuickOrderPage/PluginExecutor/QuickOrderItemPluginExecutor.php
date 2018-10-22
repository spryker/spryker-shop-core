<?php
/**
 * Created by PhpStorm.
 * User: karolygerner
 * Date: 22.October.2018
 * Time: 11:27
 */

namespace SprykerShop\Yves\QuickOrderPage\PluginExecutor;


use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemFilterPluginInterface;

class QuickOrderItemPluginExecutor implements QuickOrderItemPluginExecutorInterface
{
    /**
     * @var QuickOrderItemFilterPluginInterface[]
     */
    protected $quickOrderItemFilterPlugins;

    /**
     * @param QuickOrderItemFilterPluginInterface[] $quickOrderItemFilterPlugins
     */
    public function __construct(array $quickOrderItemFilterPlugins)
    {
        $this->quickOrderItemFilterPlugins = $quickOrderItemFilterPlugins;
    }

    /**
     * @param QuickOrderTransfer $quickOrderTransfer
     * @param ProductConcreteTransfer[] $products
     *
     * @return QuickOrderTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrder(QuickOrderTransfer $quickOrderTransfer, array $products): QuickOrderTransfer
    {
        $quickOrderItems = [];
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            $quickOrderItems[] = $this->applyQuickOrderItemFilterPluginsOnQuickOrderItem($quickOrderItemTransfer, $products[$quickOrderItemTransfer->getSku()] ?? null);
        }
        $quickOrderTransfer->setItems(new \ArrayObject($quickOrderItems));

        return $quickOrderTransfer;
    }

    /**
     * @param QuickOrderItemTransfer $quickOrderItemTransfer
     * @param ProductConcreteTransfer|null $product
     *
     * @return QuickOrderItemTransfer
     */
    public function applyQuickOrderItemFilterPluginsOnQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer, ?ProductConcreteTransfer $product): QuickOrderItemTransfer
    {
        if (empty($quickOrderItemTransfer->getSku())) {
            return $quickOrderItemTransfer;
        }

        if (empty($product)) {
            return $quickOrderItemTransfer;
        }

        foreach ($this->quickOrderItemFilterPlugins as $quickOrderItemFilterPlugin) {
            $quickOrderItemTransfer = $quickOrderItemFilterPlugin->filterItem($quickOrderItemTransfer, $product);
        }

        return $quickOrderItemTransfer;
    }
}
