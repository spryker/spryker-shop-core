<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductGroupWidget\ProductGroupWidgetFactory getFactory()
 */
class ProductGroupWidget extends AbstractWidget
{
    /**
     * @param int $idProductAbstract
     * @param string $template
     *
     * @return void
     */
    public function initialize($idProductAbstract, $template): void
    {
        $this
            ->addParameter('productGroupItems', $this->getProductGroups($idProductAbstract))
            ->addParameter('idProductAbstract', $idProductAbstract)
            ->addParameter('template', $template);
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductGroupWidget';
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductGroupWidget/views/product-group/product-group.twig';
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
            $productData = $this->getFactory()->getProductStorageClient()->findProductAbstractStorageData($idProductAbstract, $this->getLocale());
            if (!$productData) {
                continue;
            }

            $productViewTransfers[] = $this->getFactory()->getProductStorageClient()->mapProductStorageData($productData, $this->getLocale());
        }

        return $productViewTransfers;
    }
}
