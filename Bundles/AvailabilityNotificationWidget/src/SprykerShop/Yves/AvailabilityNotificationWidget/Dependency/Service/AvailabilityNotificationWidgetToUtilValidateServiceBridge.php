<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Dependency\Service;

class AvailabilityNotificationWidgetToUtilValidateServiceBridge implements AvailabilityNotificationWidgetToUtilValidateServiceInterface
{
    /**
     * @var \Spryker\Service\UtilValidate\UtilValidateServiceInterface
     */
    protected $utilValidateService;

    /**
     * @param \Spryker\Service\UtilValidate\UtilValidateServiceInterface $utilValidateService
     */
    public function __construct($utilValidateService)
    {
        $this->utilValidateService = $utilValidateService;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isEmailFormatValid(string $email): bool
    {
        return $this->utilValidateService->isEmailFormatValid($email);
    }
}
