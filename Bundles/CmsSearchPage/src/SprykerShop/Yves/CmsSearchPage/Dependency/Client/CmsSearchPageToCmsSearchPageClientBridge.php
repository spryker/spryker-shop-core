<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSearchPage\Dependency\Client;

class CmsSearchPageToCmsSearchPageClientBridge implements CmsSearchPageToCmsPageSearchClientInterface
{
    /**
     * @var \Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface
     */
    protected $cmsPageSearchClient;

    /**
     * @param \Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface $cmsPageSearchClient
     */
    public function __construct($cmsPageSearchClient)
    {
        $this->cmsPageSearchClient = $cmsPageSearchClient;
    }

    /**
     * @param string $searchString
     * @param array<string, mixed> $requestParameters
     *
     * @return array
     */
    public function search(string $searchString, array $requestParameters = []): array
    {
        return $this->cmsPageSearchClient->search($searchString, $requestParameters);
    }

    /**
     * @param string $searchString
     * @param array<string, mixed> $requestParameters
     *
     * @return int
     */
    public function searchCount(string $searchString, array $requestParameters = []): int
    {
        return $this->cmsPageSearchClient->searchCount($searchString, $requestParameters);
    }
}
