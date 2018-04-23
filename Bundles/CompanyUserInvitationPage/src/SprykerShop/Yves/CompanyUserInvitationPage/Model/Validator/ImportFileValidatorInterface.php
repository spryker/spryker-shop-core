<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Model\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImportFileValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return bool
     */
    public function isValidImportFile(UploadedFile $uploadedFile): bool;
}
