<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Client;

interface CatalogPageToCategoryClientInterface
{
    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return string
     */
    public function getTemplatePathByNodeId($idCategoryNode, $localeName);
}
