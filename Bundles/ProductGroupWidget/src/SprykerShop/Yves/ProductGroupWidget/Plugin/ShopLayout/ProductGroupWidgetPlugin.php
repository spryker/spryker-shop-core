<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Plugin\ShopLayout;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductGroupWidget\ProductGroupWidgetFactory;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\ProductGroupWidget\ProductGroupWidgetPluginInterface;

/**
 * Class ProductGroupWidgetPlugin
 *
 * @method ProductGroupWidgetFactory getFactory()
 */
class ProductGroupWidgetPlugin extends AbstractWidgetPlugin implements ProductGroupWidgetPluginInterface
{

    /**
     * @param int $idProductAbstract
     * @param string $template
     *
     * @return void
     */
    public function initialize($idProductAbstract, $template): void
    {
        $this->addParameter('productGroupItems', $this->getProductGroups($idProductAbstract))
            ->addParameter('template', $template);
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductGroupWidget/_product-group/_main.twig';
    }

    /**
     * @param int $idProductAbstract
     *
     * @return ProductViewTransfer[]
     */
    protected function getProductGroups($idProductAbstract)
    {
        $productGroup = $this->getFactory()->getProductGroupStorageClient()->findProductGroupItemsByIdProductAbstract($idProductAbstract, $this->getLocale());
        $productViewTransfers = [];
        foreach ($productGroup->getGroupProductAbstractIds() as $idProductAbstract) {
            $productData = $this->getFactory()->getProductStorageClient()->getProductAbstractStorageData($idProductAbstract, $this->getLocale());
            $productViewTransfers[] = $this->getFactory()->getProductStorageClient()->mapProductStorageData($productData, $this->getLocale());
        }

        return $productViewTransfers;
    }
}
