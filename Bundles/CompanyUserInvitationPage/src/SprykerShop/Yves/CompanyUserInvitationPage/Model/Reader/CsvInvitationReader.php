<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Model\Reader;

use Iterator;
use League\Csv\Reader;

class CsvInvitationReader implements InvitationReaderInterface
{
    /**
     * @var string
     */
    protected $importFilePath;

    /**
     * @param string $importFilePath
     */
    public function __construct(string $importFilePath)
    {
        $this->importFilePath = $importFilePath;
    }

    /**
     * @return \Iterator
     */
    public function getInvitations(): Iterator
    {
        return Reader::createFromPath($this->importFilePath, 'r')->fetchAssoc();
    }
}
