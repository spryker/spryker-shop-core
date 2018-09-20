<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\CatalogPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CatalogPage\Dependency\Plugin\CmsBlockWidget\CatalogCmsBlockWidgetPluginInterface;
use SprykerShop\Yves\CmsBlockWidget\Widget\CatalogWithCmsBlockWidget;

/**
 * @deprecated Use \SprykerShop\Yves\CmsBlockWidget\Widget\CatalogWithCmsBlockWidget instead.
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
        $widget = new CatalogWithCmsBlockWidget($idCategory);

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
        return CatalogWithCmsBlockWidget::getTemplate();
    }
}
