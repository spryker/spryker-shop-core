<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductSetWidget\Dependency\Client;

use Generated\Shared\Transfer\ContentProductSetTypeTransfer;

interface ContentProductSetWidgetToContentProductSetClientInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductSetTypeTransfer|null
     */
    public function executeProductSetTypeById(int $idContent, string $localeName): ?ContentProductSetTypeTransfer;
}
