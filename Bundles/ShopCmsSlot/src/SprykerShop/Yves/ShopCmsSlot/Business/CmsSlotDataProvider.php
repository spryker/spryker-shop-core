<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotDataTransfer;
use Generated\Shared\Transfer\CmsSlotRequestTransfer;
use SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface;
use SprykerShop\Yves\ShopCmsSlot\Exception\MissingRequiredParameterException;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface;

class CmsSlotDataProvider implements CmsSlotDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface
     */
    protected $cmsSlotContentPlugin;

    /**
     * @var \SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface
     */
    protected $cmsSlotClient;

    /**
     * @param \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface $cmsSlotContentPlugin
     * @param \SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface $cmsSlotClient
     */
    public function __construct(
        CmsSlotContentPluginInterface $cmsSlotContentPlugin,
        ShopCmsSlotToCmsSlotClientInterface $cmsSlotClient
    ) {
        $this->cmsSlotContentPlugin = $cmsSlotContentPlugin;
        $this->cmsSlotClient = $cmsSlotClient;
    }

    /**
     * @param string $cmsSlotKey
     * @param array $providedData
     * @param string[] $requiredKeys
     * @param string[] $autoFillingKeys
     *
     * @return \Generated\Shared\Transfer\CmsSlotDataTransfer
     */
    public function getSlotContent(
        string $cmsSlotKey,
        array $providedData,
        array $requiredKeys,
        array $autoFillingKeys
    ): CmsSlotDataTransfer {
        if ($autoFillingKeys) {
            $autoFilledData = $this->cmsSlotClient->getCmsSlotExternalDataByKeys($autoFillingKeys);
            $providedData = $autoFilledData + $providedData;
        }

        if ($requiredKeys) {
            $this->assureProvidedHasRequiredKeys($providedData, $requiredKeys);
        }

        $cmsSlotRequestTransfer = (new CmsSlotRequestTransfer())
            ->setCmsSlotKey($cmsSlotKey)
            ->setParams($providedData);

        return $this->cmsSlotContentPlugin->getSlotContent($cmsSlotRequestTransfer);
    }

    /**
     * @param array $provided
     * @param array $requiredKeys
     *
     * @throws \SprykerShop\Yves\ShopCmsSlot\Exception\MissingRequiredParameterException
     *
     * @return void
     */
    protected function assureProvidedHasRequiredKeys(array $provided, array $requiredKeys): void
    {
        foreach ($requiredKeys as $requiredKey) {
            if (!isset($provided[$requiredKey])) {
                throw new MissingRequiredParameterException(
                    sprintf('The "%s" param is missing in the provided data', $requiredKey)
                );
            }
        }
    }
}
