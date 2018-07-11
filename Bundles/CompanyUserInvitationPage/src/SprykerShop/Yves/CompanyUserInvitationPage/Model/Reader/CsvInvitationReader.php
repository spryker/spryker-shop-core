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
     * @var \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageConfig $config
     */
    public function __construct(CompanyUserInvitationPageConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $importFilePath
     *
     * @return mixed
     */
    public function getHeaders(string $importFilePath)
    {
        return Reader::createFromPath($importFilePath, 'r')
            ->setDelimiter($this->config->getInvitationFileDelimiter())
            ->getIterator()
            ->current();
    }

    /**
     * @param string $importFilePath
     *
     * @return \Iterator
     */
    public function getData(string $importFilePath): Iterator
    {
        return Reader::createFromPath($importFilePath, 'r')
            ->setDelimiter($this->config->getInvitationFileDelimiter())
            ->setHeaderOffset(0)
            ->getRecords();
    }
}
