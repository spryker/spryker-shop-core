<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductWidget\Dependency\Client;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;

class ContentProductWidgetToContentProductClientBridge implements ContentProductWidgetToContentProductClientBridgeInterface
{
    /**
     * @var \Spryker\Client\ContentProduct\ContentProductClientInterface
     */
    protected $contentProductClient;

    /**
     * @param \Spryker\Client\ContentProduct\ContentProductClientInterface $contentProductClient
     */
    public function __construct($contentProductClient)
    {
        $this->contentProductClient = $contentProductClient;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null
     */
    public function executeProductAbstractListTypeByKey(string $contentKey, string $localeName): ?ContentProductAbstractListTypeTransfer
    {
        return $this->contentProductClient->executeProductAbstractListTypeByKey($contentKey, $localeName);
    }
}
