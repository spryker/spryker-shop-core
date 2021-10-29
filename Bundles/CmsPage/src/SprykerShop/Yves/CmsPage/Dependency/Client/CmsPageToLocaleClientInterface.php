<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage\Dependency\Client;

interface CmsPageToLocaleClientInterface
{
    /**
     * @return array<string>
     */
    public function getLocales(): array;
}
