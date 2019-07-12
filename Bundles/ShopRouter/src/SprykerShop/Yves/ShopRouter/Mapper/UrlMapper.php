<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Mapper;

/**
 * @deprecated Use `spryker/router` instead.
 */
class UrlMapper implements UrlMapperInterface
{
    /**
     * @param array $mergedParameters
     *
     * @return string
     */
    public function generateUrlFromParameters(array $mergedParameters)
    {
        if (!$mergedParameters) {
            return '';
        }

        return '?' . http_build_query($mergedParameters);
    }
}
