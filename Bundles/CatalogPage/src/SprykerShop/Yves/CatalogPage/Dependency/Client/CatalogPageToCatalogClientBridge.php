<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Client;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CatalogPageToCatalogClientBridge implements CatalogPageToCatalogClientInterface
{
    /**
     * @var \Spryker\Client\Catalog\CatalogClientInterface
     */
    protected $catalogClient;

    /**
     * @param \Spryker\Client\Catalog\CatalogClientInterface $catalogClient
     */
    public function __construct($catalogClient)
    {
        $this->catalogClient = $catalogClient;
    }

    /**
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSearch($searchString, array $requestParameters = [])
    {
        return $this->catalogClient->catalogSearch($searchString, $requestParameters);
    }

    /**
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSuggestSearch($searchString, array $requestParameters = [])
    {
        return $this->catalogClient->catalogSuggestSearch($searchString, $requestParameters);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getCatalogViewMode(Request $request)
    {
        return $this->catalogClient->getCatalogViewMode($request);
    }

    /**
     * @param string $mode
     * @param Response $response
     *
     * @return Response
     */
    public function setCatalogViewMode($mode, Response $response)
    {
        return $this->catalogClient->setCatalogViewMode($mode, $response);
    }
}
