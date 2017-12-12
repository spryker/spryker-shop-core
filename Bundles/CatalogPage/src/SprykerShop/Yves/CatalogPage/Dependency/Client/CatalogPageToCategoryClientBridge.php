<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Client;

class CatalogPageToCategoryClientBridge implements CatalogPageToCategoryClientInterface
{
    /**
     * @var \Spryker\Client\Category\CategoryClientInterface
     */
    protected $categoryClient;

    /**
     * @param \Spryker\Client\Category\CategoryClientInterface $categoryClient
     */
    public function __construct($categoryClient)
    {
        $this->categoryClient = $categoryClient;
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return string
     */
    public function getTemplatePathByNodeId($idCategoryNode, $localeName)
    {
        return $this->categoryClient->getTemplatePathByNodeId($idCategoryNode, $localeName);
    }
}
