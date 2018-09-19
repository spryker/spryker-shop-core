<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget\Plugin\ProductWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductLabelWidget\Widget\ProductAbstractLabelWidget;
use SprykerShop\Yves\ProductWidget\Dependency\Plugin\ProductLabelWidget\ProductAbstractLabelWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductLabelWidget\Widget\ProductAbstractLabelWidget instead.
 *
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
        $widget = new ProductAbstractLabelWidget($idProductAbstract);

        $this->parameters = $widget->getParameters();
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
        return ProductAbstractLabelWidget::getTemplate();
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
