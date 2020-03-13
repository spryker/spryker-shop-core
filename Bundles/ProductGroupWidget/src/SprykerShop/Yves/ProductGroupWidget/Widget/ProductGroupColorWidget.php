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
class ProductGroupColorWidget extends AbstractWidget
{
    /**
     * @param int $idProductAbstract
     * @param array $selectedAttributes
     */
    public function __construct(int $idProductAbstract, array $selectedAttributes = [])
    {
        $productViewTransfers = $this->getFactory()
            ->getProductGroupReader()
            ->getProductGroups($idProductAbstract, $this->getLocale(), $selectedAttributes);

        $this->addParameter('productGroupItems', $productViewTransfers)
            ->addParameter('idProductAbstract', $idProductAbstract);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductGroupColorWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductGroupWidget/views/product-group/product-group.twig';
    }
}
