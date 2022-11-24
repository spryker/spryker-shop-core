<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Service;

use Generated\Shared\Transfer\NumberFormatConfigTransfer;

class ConfigurableBundleWidgetToUtilNumberServiceBridge implements ConfigurableBundleWidgetToUtilNumberServiceInterface
{
    /**
     * @var \Spryker\Service\UtilNumber\UtilNumberServiceInterface
     */
    protected $utilNumberService;

    /**
     * @param \Spryker\Service\UtilNumber\UtilNumberServiceInterface $utilNumberService
     */
    public function __construct($utilNumberService)
    {
        $this->utilNumberService = $utilNumberService;
    }

    /**
     * @param string|null $locale
     *
     * @return \Generated\Shared\Transfer\NumberFormatConfigTransfer
     */
    public function getNumberFormatConfig(?string $locale = null): NumberFormatConfigTransfer
    {
        return $this->utilNumberService->getNumberFormatConfig($locale);
    }
}
