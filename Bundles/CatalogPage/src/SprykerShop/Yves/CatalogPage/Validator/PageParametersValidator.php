<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Validator;

use SprykerShop\Yves\CatalogPage\CatalogPageConfig;

class PageParametersValidator implements PageParametersValidatorInterface
{
    /**
     * @var \SprykerShop\Yves\CatalogPage\CatalogPageConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\CatalogPage\CatalogPageConfig $config
     */
    public function __construct(CatalogPageConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string[] $parameters
     *
     * @return bool
     */
    public function validatePageParameters(array $parameters): bool
    {
        $catalogPageLimit = $this->config->getCatalogPageLimit();
        $page = $this->config->getParameterNamePage();
        $defaultItemsPerPage = $this->config->getDefaultItemsPerPage();
        $itemsPerPage = $this->config->getParameterItemsPerPage();

        $divider = $parameters[$itemsPerPage] ?? $defaultItemsPerPage;

        if (isset($parameters[$page]) && $parameters[$page] > $catalogPageLimit / $divider) {
            return false;
        }

        return true;
    }
}
