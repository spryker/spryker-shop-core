<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Model\Validator;

use Exception;
use SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageConfig;
use SprykerShop\Yves\CompanyUserInvitationPage\Model\Reader\InvitationReaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportFileValidator implements ImportFileValidatorInterface
{
    /**
     * @var \SprykerShop\Yves\CompanyUserInvitationPage\Model\Reader\InvitationReaderInterface
     */
    private $invitationReader;

    /**
     * @param \SprykerShop\Yves\CompanyUserInvitationPage\Model\Reader\InvitationReaderInterface $invitationReader
     */
    public function __construct(InvitationReaderInterface $invitationReader)
    {
        $this->invitationReader = $invitationReader;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return bool
     */
    public function isValidImportFile(UploadedFile $uploadedFile): bool
    {
        try {
            $headers = $this->invitationReader->getHeaders($uploadedFile->getPathName());

            return !array_diff([
                CompanyUserInvitationPageConfig::COLUMN_FIRST_NAME,
                CompanyUserInvitationPageConfig::COLUMN_LAST_NAME,
                CompanyUserInvitationPageConfig::COLUMN_EMAIL,
                CompanyUserInvitationPageConfig::COLUMN_BUSINESS_UNIT,
            ], $headers);
        } catch (Exception $e) {
            return false;
        }
    }
}
