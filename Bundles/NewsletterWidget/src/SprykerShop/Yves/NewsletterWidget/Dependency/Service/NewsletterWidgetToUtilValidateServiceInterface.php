<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\Dependency\Service;

interface NewsletterWidgetToUtilValidateServiceInterface
{
    /**
     * @param string $email
     *
     * @return bool
     */
    public function isEmailFormatValid($email);
}
