<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Plugin\CmsBlockWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Needs to be refactored by Alessadro Bellini.
 */
interface CatalogCmsBlockWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'CatalogCmsBlockWidgetPlugin';

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function initialize(int $idCategory): void;
}
