<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Client;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CatalogPageToCatalogClientInterface
{
    /**
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSearch($searchString, array $requestParameters = []);

    /**
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSuggestSearch($searchString, array $requestParameters = []);

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getCatalogViewMode(Request $request);

    /**
     * @param string $mode
     * @param Response $response
     *
     * @return Response
     */
    public function setCatalogViewMode($mode, Response $response);
}
