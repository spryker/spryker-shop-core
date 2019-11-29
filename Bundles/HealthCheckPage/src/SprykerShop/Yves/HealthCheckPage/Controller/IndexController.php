<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HealthCheckPage\Controller;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\HealthCheckPage\HealthCheckPageFactory getFactory()
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
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setServices($request->get(static::KEY_HEALTH_CHECK_SERVICES));

        $healthCheckResponseTransfer = $this->getFactory()
            ->getHealthCheckClient()
            ->executeHealthCheck($healthCheckRequestTransfer);

        return new JsonResponse($healthCheckResponseTransfer->toArray());
    }
}
