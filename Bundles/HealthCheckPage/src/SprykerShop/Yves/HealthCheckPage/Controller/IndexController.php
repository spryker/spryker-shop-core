<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HealthCheckPage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\HealthCheckPage\HealthCheckPageFactory getFactory()
 * @method \SprykerShop\Yves\HealthCheckPage\HealthCheckPageConfig getConfig()
 */
class IndexController extends AbstractController
{
    protected const KEY_HEALTH_CHECK_SERVICES = 'services';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $healthCheckResponseTransfer = $this->getFactory()
            ->createHealthChecker()
            ->executeHealthCheck($request->query->get(static::KEY_HEALTH_CHECK_SERVICES));

        return new JsonResponse($healthCheckResponseTransfer->toArray());
    }
}
