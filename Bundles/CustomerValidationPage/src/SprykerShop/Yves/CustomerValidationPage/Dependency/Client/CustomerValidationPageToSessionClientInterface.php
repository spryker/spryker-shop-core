<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerValidationPage\Dependency\Client;

use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

interface CustomerValidationPageToSessionClientInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag
     */
    public function getMetadataBag(): MetadataBag;
}
