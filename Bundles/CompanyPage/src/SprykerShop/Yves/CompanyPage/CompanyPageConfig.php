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
     * @var int
     */
    protected const MIN_LENGTH_COMPANY_USER_PASSWORD = 12;

    /**
     * @var int
     */
    protected const MAX_LENGTH_COMPANY_USER_PASSWORD = 128;

    /**
     * @var string
     */
    protected const PASSWORD_VALIDATION_PATTERN = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()\_\-\=\+\[\]\{\}\|;:<>.,\/?\\~])[A-Za-z\d!@#$%^&*()\_\-\=\+\[\]\{\}\|;:<>.,\/?\\~]+$/';

    /**
     * @var string
     */
    protected const PASSWORD_VALIDATION_MESSAGE = 'global.password.invalid_password';

    /**
     * @api
     *
     * @return string
     */
    public function getZipCodeConstraintPattern(): string
    {
        return static::ZIP_CODE_CONSTRAINT_PATTERN;
    }

    /**
     * Specification:
     * - Returns the minimum length for company user password.
     *
     * @api
     *
     * @return int
     */
    public function getCompanyUserPasswordMinLength(): int
    {
        return static::MIN_LENGTH_COMPANY_USER_PASSWORD;
    }

    /**
     * Specification:
     * - Returns the maximum length for company user password.
     *
     * @api
     *
     * @return int
     */
    public function getCompanyUserPasswordMaxLength(): int
    {
        return static::MAX_LENGTH_COMPANY_USER_PASSWORD;
    }

    /**
     * Specification:
     * - Returns the pattern for company user password validation.
     *
     * @api
     *
     * @return string
     */
    public function getCompanyUserPasswordPattern(): string
    {
        return static::PASSWORD_VALIDATION_PATTERN;
    }

    /**
     * Specification:
     * - Returns the message for company user password validation.
     *
     * @api
     *
     * @return string
     */
    public function getPasswordValidationMessage(): string
    {
        return static::PASSWORD_VALIDATION_MESSAGE;
    }
}
