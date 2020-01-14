<?php

namespace SprykerShop\Yves\CompanyPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CompanyPageConfig extends AbstractBundleConfig
{
    protected const ZIP_CODE_CONSTRAINT_PATTERN = '/^\d{5}$/';

    /**
     * @return string
     */
    public function getZipCodeConstraintPattern(): string
    {
        return static::ZIP_CODE_CONSTRAINT_PATTERN;
    }
}
