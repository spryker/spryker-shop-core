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

    public const INVITATION_SESSION_ID = 'COMPANY_USER_INVITATION';
    public const INVITATION_IMPORT_ERRORS_FILE = 'INVITATION_IMPORT_ERRORS_FILE';
    public const IMPORT_ERRORS_FILE_PREFIX = '_IMPORT_ERRORS_FILE_';

    /**
     * @api
     *
     * @return string
     */
    public function getInvitationFileDelimiter(): string
    {
        return static::INVITATION_FILE_DELIMITER;
    }
}
