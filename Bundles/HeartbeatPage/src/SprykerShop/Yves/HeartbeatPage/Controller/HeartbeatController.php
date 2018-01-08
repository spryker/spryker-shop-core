<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HeartbeatPage\Controller;

use Spryker\Shared\Storage\StorageConstants;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\HeartbeatPage\HeartbeatPageFactory getFactory()
 */
class HeartbeatController extends AbstractController
{
    const SYSTEM_UP = 'UP';
    const SYSTEM_DOWN = 'DOWN';
    const SYSTEM_STATUS = 'status';
    const STATUS_REPORT = 'report';
    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INACTIVE;

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction()
    {
        $healthChecker = $this
            ->getFactory()
            ->createHealthChecker()
            ->doHealthCheck();

        if ($healthChecker->isSystemAlive()) {
            return $this->jsonResponse(
                [
                    self::SYSTEM_STATUS => self::SYSTEM_UP,
                ],
                Response::HTTP_OK
            );
        }

        return $this->jsonResponse(
            [
                self::SYSTEM_STATUS => self::SYSTEM_DOWN,
                self::STATUS_REPORT => $healthChecker->getReport()->toArray(),
            ],
            Response::HTTP_SERVICE_UNAVAILABLE
        );
    }
}
