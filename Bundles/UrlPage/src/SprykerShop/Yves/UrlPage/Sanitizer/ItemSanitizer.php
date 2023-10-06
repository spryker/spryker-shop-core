<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\UrlPage\Sanitizer;

use SprykerShop\Yves\UrlPage\Dependency\Client\UrlPageToUrlStorageClientInterface;

class ItemSanitizer implements ItemSanitizerInterface
{
    /**
     * @var \SprykerShop\Yves\UrlPage\Dependency\Client\UrlPageToUrlStorageClientInterface
     */
    protected UrlPageToUrlStorageClientInterface $urlStorageClient;

    /**
     * @param \SprykerShop\Yves\UrlPage\Dependency\Client\UrlPageToUrlStorageClientInterface $urlStorageClient
     */
    public function __construct(
        UrlPageToUrlStorageClientInterface $urlStorageClient
    ) {
        $this->urlStorageClient = $urlStorageClient;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function sanitizeInvalidItemUrls(array $itemTransfers): array
    {
        $urls = $this->extractUrlsFromItemTransfers($itemTransfers);

        if (!$urls) {
            return $itemTransfers;
        }

        $urlStorageTransfersIndexedByUrl = $this->urlStorageClient->getUrlStorageTransferByUrls($urls);

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getUrl() || isset($urlStorageTransfersIndexedByUrl[$itemTransfer->getUrlOrFail()])) {
                continue;
            }

            $itemTransfer->setUrl(null);
        }

        return $itemTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<string>
     */
    protected function extractUrlsFromItemTransfers(array $itemTransfers): array
    {
        $urls = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getUrl()) {
                continue;
            }

            $urls[] = $itemTransfer->getUrlOrFail();
        }

        return $urls;
    }
}
