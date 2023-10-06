<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\UrlPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\UrlPage\Dependency\Client\UrlPageToUrlStorageClientInterface;
use SprykerShop\Yves\UrlPage\Sanitizer\ItemSanitizer;
use SprykerShop\Yves\UrlPage\Sanitizer\ItemSanitizerInterface;

class UrlPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\UrlPage\Sanitizer\ItemSanitizerInterface
     */
    public function createItemSanitizer(): ItemSanitizerInterface
    {
        return new ItemSanitizer(
            $this->getUrlStorageClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\UrlPage\Dependency\Client\UrlPageToUrlStorageClientInterface
     */
    public function getUrlStorageClient(): UrlPageToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(UrlPageDependencyProvider::CLIENT_URL_STORAGE);
    }
}
