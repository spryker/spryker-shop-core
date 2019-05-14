<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\CatalogPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CatalogPage\Dependency\Plugin\CmsBlockWidget\CatalogCmsBlockWidgetPluginInterface;

/**
 * @deprecated Use molecule('catalog-cms-block', 'CmsBlockWidget') instead.
 */
class CatalogCmsBlockWidgetPlugin extends AbstractWidgetPlugin implements CatalogCmsBlockWidgetPluginInterface
{
    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function initialize(int $idCategory): void
    {
        $this->addParameter('idCategory', $idCategory);
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
        return '@CmsBlockWidget/views/catalog-with-cms-block/catalog-with-cms-block.twig';
    }
}
