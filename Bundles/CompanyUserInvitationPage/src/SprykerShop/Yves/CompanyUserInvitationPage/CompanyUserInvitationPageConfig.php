<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;

class CompanyUserInvitationPageConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getInvitationFileDelimiter(): string
    {
        return $this->get(CompanyUserInvitationPageConstants::INVITATION_FILE_DELIMITER);
    }
}
