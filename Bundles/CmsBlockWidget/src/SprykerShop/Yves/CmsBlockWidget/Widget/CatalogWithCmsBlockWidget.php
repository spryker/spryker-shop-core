<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class CatalogWithCmsBlockWidget extends AbstractWidget
{
    /**
     * @param int $idCategory
     * @param string $position
     */
    public function __construct(int $idCategory, string $position)
    {
        $this->addParameter('idCategory', $idCategory)
            ->addParameter('position', $position);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CatalogWithCmsBlockWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CmsBlockWidget/views/catalog-with-cms-block/catalog-with-cms-block.twig';
    }
}
