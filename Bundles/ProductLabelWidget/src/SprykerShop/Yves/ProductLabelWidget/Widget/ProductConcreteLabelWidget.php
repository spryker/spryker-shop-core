<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductLabelWidget\ProductLabelWidgetFactory getFactory()
 */
class ProductConcreteLabelWidget extends AbstractWidget
{
    /**
     * @param array $idProductLabels
     */
    public function __construct(array $idProductLabels)
    {
        $this->addParameter('idProductLabels', $idProductLabels)
            ->addParameter('productLabelDictionaryItemTransfers', $this->getProductLabelDictionaryItems($idProductLabels));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductConcreteLabelWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductLabelWidget/views/product-label-group/product-label-group.twig';
    }

    /**
     * @param array $idProductLabels
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    protected function getProductLabelDictionaryItems(array $idProductLabels)
    {
        return $this->getFactory()
            ->getProductLabelStorageClient()
            ->findLabels($idProductLabels, $this->getLocale(), APPLICATION_STORE);
    }
}
