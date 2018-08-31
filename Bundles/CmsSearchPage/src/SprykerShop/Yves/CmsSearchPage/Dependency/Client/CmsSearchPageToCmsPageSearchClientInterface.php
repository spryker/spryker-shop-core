<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage\Dependency\Client;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CmsSearchPageToCmsPageSearchClientInterface
{
    /**
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function search(string $searchString, array $requestParameters = []): array;
}
