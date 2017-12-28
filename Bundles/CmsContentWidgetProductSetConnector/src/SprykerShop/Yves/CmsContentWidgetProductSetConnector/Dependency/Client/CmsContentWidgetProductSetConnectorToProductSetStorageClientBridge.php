<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductSetConnector\Dependency\Client;

use Spryker\Client\ProductSetStorage\ProductSetStorageClientInterface;

class CmsContentWidgetProductSetConnectorToProductSetStorageClientBridge implements CmsContentWidgetProductSetConnectorToProductSetStorageClientInterface
{

    /**
     * @var ProductSetStorageClientInterface
     */
    protected $productSetStorageClient;

    /**
     * @param ProductSetStorageClientInterface $productSetStorageClient
     */
    public function __construct($productSetStorageClient)
    {
        $this->productSetStorageClient = $productSetStorageClient;
    }

    /**
     * @param int $idProductSet
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer|null
     */
    public function getProductSetByIdProductSet($idProductSet, $localeName)
    {
        return $this->productSetStorageClient->getProductSetByIdProductSet($idProductSet, $localeName);
    }
}
