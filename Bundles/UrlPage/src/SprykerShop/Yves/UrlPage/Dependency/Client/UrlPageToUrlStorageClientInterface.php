<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\UrlPage\Dependency\Client;

interface UrlPageToUrlStorageClientInterface
{
    /**
     * @param list<string> $urlCollection
     *
     * @return array<string, \Generated\Shared\Transfer\UrlStorageTransfer>
     */
    public function getUrlStorageTransferByUrls(array $urlCollection): array;
}
