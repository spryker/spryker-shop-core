<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Client;

interface CompanyPageToGlossaryStorageClientInterface
{
    /**
     * @param string $id
     * @param string $localeName
     * @param array<string, mixed> $parameters
     *
     * @return string
     */
    public function translate($id, $localeName, array $parameters = []);
}
