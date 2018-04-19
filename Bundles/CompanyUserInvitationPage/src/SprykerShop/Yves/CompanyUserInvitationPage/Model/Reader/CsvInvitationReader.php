<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Model\Reader;

use Iterator;
use League\Csv\Reader;
use SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageConfig;

class CsvInvitationReader implements InvitationReaderInterface
{
    /**
     * @var string
     */
    protected $importFilePath;

    /**
     * @var \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageConfig
     */
    protected $config;

    /**
     * @param string $importFilePath
     * @param \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageConfig|\Spryker\Yves\Kernel\AbstractBundleConfig $config
     */
    public function __construct(
        string $importFilePath,
        CompanyUserInvitationPageConfig $config
    ) {
        $this->importFilePath = $importFilePath;
        $this->config = $config;
    }

    /**
     * @return \Iterator
     */
    public function getInvitations(): Iterator
    {
        return Reader::createFromPath($this->importFilePath, 'r')
            ->setDelimiter($this->config->getInvitationFileDelimiter())
            ->fetchAssoc();
    }
}
