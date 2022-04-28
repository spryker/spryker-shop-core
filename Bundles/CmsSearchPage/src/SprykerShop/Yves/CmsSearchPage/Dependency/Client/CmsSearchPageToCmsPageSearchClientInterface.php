<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage\Dependency\Client;

interface CmsSearchPageToCmsPageSearchClientInterface
{
    /**
     * @param string $searchString
     * @param array<string, mixed> $requestParameters
     *
     * @return array
     */
    public function search(string $searchString, array $requestParameters = []): array;

    /**
     * @param string $searchString
     * @param array<string, mixed> $requestParameters
     *
     * @return int
     */
    public function searchCount(string $searchString, array $requestParameters = []): int;
}
