<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Purifier;

class RequestAttributesPurifier implements RequestAttributesPurifierInterface
{
    /**
     * @param array $requestAttributes
     *
     * @return array
     */
    public function purifyRequestAttributes(array $requestAttributes): array
    {
        foreach ($requestAttributes as $key => $value) {
            if (is_array($value)) {
                $value = $this->purifyRequestAttributes($value);
            }

            if (!$value) {
                unset($requestAttributes[$key]);
            }
        }

        return $requestAttributes;
    }
}
