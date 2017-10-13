<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Plugin\CmsBlockWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CatalogCmsBlockWidgetPluginInterface extends WidgetPluginInterface
{

    public const NAME = 'CatalogCmsBlockWidgetPlugin';

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function initialize(int $idCategory): void;

}
