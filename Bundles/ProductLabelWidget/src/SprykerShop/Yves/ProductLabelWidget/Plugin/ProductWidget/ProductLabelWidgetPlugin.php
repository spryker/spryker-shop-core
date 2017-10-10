<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget\Plugin\ProductWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductWidget\Dependency\Plugin\ProductLabelWidget\ProductLabelWidgetPluginInterface;

class ProductLabelWidgetPlugin extends AbstractWidgetPlugin implements ProductLabelWidgetPluginInterface
{

    /**
     * @param array $idProductLabels
     *
     * @return void
     */
    public function initialize(array $idProductLabels): void
    {
        $this->addParameter('idProductLabels', $idProductLabels);
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
        return '@ProductLabelWidget/_product-widget/product-labels.twig';
    }

}
