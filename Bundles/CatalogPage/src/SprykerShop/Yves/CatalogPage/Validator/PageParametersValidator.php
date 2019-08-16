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
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::DEFAULT_ITEMS_PER_PAGE;
     */
    protected const DEFAULT_ITEMS_PER_PAGE = 12;

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::PARAMETER_NAME_PAGE;
     */
    protected const PARAMETER_NAME_PAGE = 'page';

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::PARAMETER_NAME_ITEMS_PER_PAGE;
     */
    protected const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';

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
     * @param array $parameters
     *
     * @return bool
     */
    public function validatePageParameters(array $parameters): bool
    {
        $catalogPageLimit = $this->config->getCatalogPageLimit();

        $divider = isset($parameters[static::PARAMETER_NAME_ITEMS_PER_PAGE])
            ? $parameters[static::PARAMETER_NAME_ITEMS_PER_PAGE]
            : static::DEFAULT_ITEMS_PER_PAGE;

        if (isset($parameters[static::PARAMETER_NAME_PAGE]) && $parameters[static::PARAMETER_NAME_PAGE] > $catalogPageLimit / $divider) {
            return false;
        }

        return true;
    }
}
