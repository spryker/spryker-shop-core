<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget\Dependency\Client;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;

interface ContentBannerWidgetToContentBannerClientInterface
{
    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer|null
     */
    public function executeBannerTypeByKey(string $contentKey, string $localeName): ?ContentBannerTypeTransfer;
}
