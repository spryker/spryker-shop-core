<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\Router;

interface RouterEnvironmentConfigConstantsYves
{
    /**
     * Specification:
     * - If option set to true, the application will create a router cache on the first request of a route.
     *
     * @api
     */
    public const IS_CACHE_ENABLED = 'ROUTER_YVES:IS_CACHE_ENABLED';

    /**
     * Specification:
     * - If option set to true, the application will check if the request is secure and not excluded from https.
     * - If request is not secure and not excluded from https, the application will return a redirect response.
     * - If request is secure and page is excluded from https, the application will allow http.
     *
     * @api
     */
    public const IS_SSL_ENABLED = 'ROUTER_YVES:IS_SSL_ENABLED';

    /**
     * Specification:
     * - An array of HTTPS Excluded resources when ssl is enabled.
     * - Example: `['route-name-a' => '/url-a', 'route-name-b' => '/url-b']`
     *
     * @api
     */
    public const SSL_EXCLUDED_ROUTE_NAMES = 'ROUTER_YVES:SSL_EXCLUDED_ROUTE_NAMES';
}
