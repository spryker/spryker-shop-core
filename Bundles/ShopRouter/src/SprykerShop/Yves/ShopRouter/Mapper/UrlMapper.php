<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopRouter\Mapper;

use SprykerShop\Yves\ShopRouter\Mapper\UrlMapperInterface;

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
