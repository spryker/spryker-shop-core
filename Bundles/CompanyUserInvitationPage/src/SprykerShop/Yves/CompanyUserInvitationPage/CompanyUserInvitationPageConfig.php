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

    /**
     * @return string
     */
    public function getInvitationFileDelimiter(): string
    {
        return static::INVITATION_FILE_DELIMITER;
    }
}
