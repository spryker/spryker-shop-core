<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Plugin\ShopLayout;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\ProductGroupWidget\ProductGroupWidgetPluginInterface;

/**
 * Class ProductGroupWidgetPlugin
 *
 * @method \SprykerShop\Yves\ProductGroupWidget\ProductGroupWidgetFactory getFactory()
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
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getProductGroups($idProductAbstract)
    {
        $productGroup = $this->getFactory()->getProductGroupStorageClient()->findProductGroupItemsByIdProductAbstract($idProductAbstract);
        $productViewTransfers = [];
        foreach ($productGroup->getGroupProductAbstractIds() as $idProductAbstract) {
            $productData = $this->getFactory()->getProductStorageClient()->getProductAbstractStorageData($idProductAbstract, $this->getLocale());
            if (!$productData) {
                continue;
            }

            $productViewTransfers[] = $this->getFactory()->getProductStorageClient()->mapProductStorageData($productData, $this->getLocale());
        }

        return $productViewTransfers;
    }
}
