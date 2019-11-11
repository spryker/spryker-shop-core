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
    protected const KEY_HEALTH_CHECK_SERVICE = 'service';

    /**
     * @param \Symfony\Component\HttpFoundation\Request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $services = $request->get(static::KEY_HEALTH_CHECK_SERVICE);
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
                ->setApplication(APPLICATION);

        if (strlen($services) !== 0) {
            $healthCheckRequestTransfer->setServices(explode(',', $services));
        }

        dump($this->getFactory()->getHealthCheckService()->processHealthCheck($healthCheckRequestTransfer)); die;
    }
}
