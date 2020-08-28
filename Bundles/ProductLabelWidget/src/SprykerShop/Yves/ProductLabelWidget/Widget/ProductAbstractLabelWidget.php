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
class ProductAbstractLabelWidget extends AbstractWidget
{
    /**
     * @param int $idProductAbstract
     */
    public function __construct(int $idProductAbstract)
    {
        $this->addParameter(
            'productLabelDictionaryItemTransfers',
            $this->getProductLabelDictionaryItems($idProductAbstract)
        );
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductAbstractLabelWidget';
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
    protected function getProductLabelDictionaryItems(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->getProductLabelStorageClient()
            ->findLabelsByIdProductAbstract($idProductAbstract, $this->getLocale(), APPLICATION_STORE);
    }
}
