<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter\ParameterMerger;

interface ParameterMergerInterface
{
    /**
     * @param array<int|string, mixed> $requestParameters
     * @param array<int|string, mixed> $generationParameters
     *
     * @return array<int|string, mixed>
     */
    public function mergeParameters(array $requestParameters, array $generationParameters);
}
