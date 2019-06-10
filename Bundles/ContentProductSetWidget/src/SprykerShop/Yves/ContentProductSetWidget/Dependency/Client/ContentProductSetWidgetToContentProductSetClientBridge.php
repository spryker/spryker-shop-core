<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductSetWidget\Dependency\Client;

use Generated\Shared\Transfer\ContentProductSetTypeTransfer;

class ContentProductSetWidgetToContentProductSetClientBridge implements ContentProductSetWidgetToContentProductSetClientInterface
{
    /**
     * @var \Spryker\Client\ContentProductSet\ContentProductSetClientInterface
     */
    protected $contentProductSetClient;

    /**
     * @param \Spryker\Client\ContentProductSet\ContentProductSetClientInterface $contentProductSetClient
     */
    public function __construct($contentProductSetClient)
    {
        $this->contentProductSetClient = $contentProductSetClient;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductSetTypeTransfer|null
     */
    public function executeProductSetTypeByKey(string $contentKey, string $localeName): ?ContentProductSetTypeTransfer
    {
        return $this->contentProductSetClient->executeProductSetTypeByKey($contentKey, $localeName);
    }
}
