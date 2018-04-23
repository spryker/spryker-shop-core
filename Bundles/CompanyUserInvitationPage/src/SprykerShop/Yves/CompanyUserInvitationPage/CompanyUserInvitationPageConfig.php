<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CompanyUserInvitationPageConfig extends AbstractBundleConfig
{
    public const INVITATION_FILE_DELIMITER = ',';

    public const COLUMN_FIRST_NAME = 'first_name';
    public const COLUMN_LAST_NAME = 'last_name';
    public const COLUMN_EMAIL = 'email';
    public const COLUMN_BUSINESS_UNIT = 'business_unit';

    /**
     * @return string
     */
    public function getInvitationFileDelimiter(): string
    {
        return static::INVITATION_FILE_DELIMITER;
    }
}
