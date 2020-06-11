<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentNavigationWidget\Dependency\Client;

use Generated\Shared\Transfer\ContentNavigationTypeTransfer;

interface ContentNavigationWidgetToContentNavigationClientInterface
{
    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTypeTransfer|null
     */
    public function executeNavigationTypeByKey(string $contentKey, string $localeName): ?ContentNavigationTypeTransfer;
}
