<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\TraceableEventWidget\Dependency\Client;

use Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;

class TraceableEventWidgetToSearchHttpClientBridge implements TraceableEventWidgetToSearchHttpClientInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\SearchHttpClientInterface
     */
    protected $searchHttpClient;

    /**
     * @param \Spryker\Client\SearchHttp\SearchHttpClientInterface $searchHttpClient
     */
    public function __construct($searchHttpClient)
    {
        $this->searchHttpClient = $searchHttpClient;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer $searchHttpConfigCriteria
     *
     * @return \Generated\Shared\Transfer\SearchHttpConfigTransfer|null
     */
    public function findSearchConfig(SearchHttpConfigCriteriaTransfer $searchHttpConfigCriteria): ?SearchHttpConfigTransfer
    {
        return $this->searchHttpClient->findSearchConfig($searchHttpConfigCriteria);
    }
}
