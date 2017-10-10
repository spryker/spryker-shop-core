<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Plugin\ProductDetailPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductGroupWidget\ProductGroupWidgetPluginInterface;

class ProductGroupWidgetPlugin extends AbstractWidgetPlugin implements ProductGroupWidgetPluginInterface
{

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function initialize(int $idProductAbstract): void
    {
        $this->idProductAbstract = $idProductAbstract;
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
        return '@ProductGroupWidget/_product-detail-page/product-groups.twig';
    }

    /**
     * @return int
     */
    public function getIdProductAbstract(): int
    {
        return $this->idProductAbstract;
    }

}
