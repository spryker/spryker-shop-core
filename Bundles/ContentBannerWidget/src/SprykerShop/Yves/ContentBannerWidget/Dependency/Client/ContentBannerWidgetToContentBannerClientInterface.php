<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryImageStorageWidget\Dependency\Client;


interface ContentBannerWidgetToContentBannerClientInterface
{
    public function getExecutedBannerById(int $idContent, string $localeName): ?ExecutedBannerTransfer;
}