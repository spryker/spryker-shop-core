<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CompanyPageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Regular expression to validate First Name field.
     *
     * @api
     *
     * @var string
     */
    public const PATTERN_FIRST_NAME = '/^[^:\/<>]+$/';

    /**
     * Specification:
     * - Regular expression to validate Last Name field.
     *
     * @api
     *
     * @var string
     */
    public const PATTERN_LAST_NAME = '/^[^:\/<>]+$/';

    /**
     * @var string
     */
    protected const ZIP_CODE_CONSTRAINT_PATTERN = '/^\d{5}$/';

    /**
     * @api
     *
     * @return string
     */
    public function getZipCodeConstraintPattern(): string
    {
        return static::ZIP_CODE_CONSTRAINT_PATTERN;
    }
}
