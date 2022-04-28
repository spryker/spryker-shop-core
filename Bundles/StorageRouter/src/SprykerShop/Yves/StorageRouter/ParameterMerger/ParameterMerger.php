<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter\ParameterMerger;

class ParameterMerger implements ParameterMergerInterface
{
    /**
     * @param array<string, mixed> $requestParameters
     * @param array $generationParameters
     *
     * @return array
     */
    public function mergeParameters(array $requestParameters, array $generationParameters)
    {
        return array_replace_recursive($requestParameters, $generationParameters);
    }
}
