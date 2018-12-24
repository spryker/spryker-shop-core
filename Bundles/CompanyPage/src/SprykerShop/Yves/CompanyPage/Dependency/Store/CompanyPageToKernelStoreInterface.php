<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Store;

interface CompanyPageToKernelStoreInterface
{
    /**
     * @return array
     */
    public function getCountries();

    /**
     * @throws \Spryker\Shared\Kernel\Locale\LocaleNotFoundException
     *
     * @return string
     */
    public function getCurrentLocale();

    /**
     * @return string[]
     */
    public function getCurrencyIsoCodes();
}
