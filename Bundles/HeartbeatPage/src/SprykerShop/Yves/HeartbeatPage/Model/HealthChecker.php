<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HeartbeatPage\Model;

use Generated\Shared\Transfer\HealthReportTransfer;
use Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface;

class HealthChecker
{
    /**
     * @var \Generated\Shared\Transfer\HealthReportTransfer
     */
    protected $healthReport;

    /**
     * @var \Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface[]
     */
    protected $healthIndicator;

    /**
     * @param array $healthIndicator
     *
     * @return void
     */
    public function setHealthIndicator(array $healthIndicator)
    {
        $this->healthIndicator = $healthIndicator;
    }

    /**
     * @return $this
     */
    public function doHealthCheck()
    {
        $this->healthReport = new HealthReportTransfer();

        foreach ($this->healthIndicator as $healthIndicator) {
            $this->check($healthIndicator);
        }

        return $this;
    }

    /**
     * @param \SprykerShop\Yves\HeartbeatPage\Model\HealthIndicator\AbstractHealthIndicator $healthIndicator
     *
     * @return void
     */
    protected function check(HealthIndicatorInterface $healthIndicator)
    {
        $healthIndicator->doHealthCheck();
        $healthIndicator->writeHealthReport($this->healthReport);
    }

    /**
     * @return \Generated\Shared\Transfer\HealthReportTransfer
     */
    public function getReport()
    {
        return $this->healthReport;
    }

    /**
     * @return bool
     */
    public function isSystemAlive()
    {
        $systemIsAlive = true;

        foreach ($this->healthReport->getHealthIndicatorReport() as $healthIndicatorReport) {
            if (!$healthIndicatorReport->getStatus()) {
                $systemIsAlive = false;
            }
        }

        return $systemIsAlive;
    }
}
