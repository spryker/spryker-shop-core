<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget\Plugin\ProductWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductWidget\Dependency\Plugin\ProductLabelWidget\ProductAbstractLabelWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductLabelWidget\ProductLabelWidgetFactory getFactory()
 */
class ProductAbstractLabelWidgetPlugin extends AbstractWidgetPlugin implements ProductAbstractLabelWidgetPluginInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function initialize(int $idProductAbstract): void
    {
        $this
            ->addParameter('idProductAbstract', $idProductAbstract)
            ->addParameter('productLabelDictionaryItemTransfers', $this->getProductLabelDictionaryItems($idProductAbstract));
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
        return '@ProductLabelWidget/views/product-label-group/product-label-group.twig';
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    protected function getProductLabelDictionaryItems($idProductAbstract)
    {
        return $this->getFactory()
            ->getProductLabelStorageClient()
            ->findLabelsByIdProductAbstract($idProductAbstract, $this->getLocale());
    }
}
