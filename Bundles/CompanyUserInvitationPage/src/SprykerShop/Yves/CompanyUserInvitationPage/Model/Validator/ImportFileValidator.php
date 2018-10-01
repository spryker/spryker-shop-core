<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Model\Validator;

use SprykerShop\Yves\CompanyUserInvitationPage\Model\Mapper\InvitationMapper;
use SprykerShop\Yves\CompanyUserInvitationPage\Model\Reader\InvitationReaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

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
            $headers = $this->invitationReader->getHeaders($uploadedFile->getPathname());

            return !array_diff([
                InvitationMapper::COLUMN_FIRST_NAME,
                InvitationMapper::COLUMN_LAST_NAME,
                InvitationMapper::COLUMN_EMAIL,
                InvitationMapper::COLUMN_BUSINESS_UNIT,
            ], $headers);
        } catch (Throwable $e) {
            return false;
        }
    }
}
