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
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotPluginInterface;

class CmsSlotExecutor implements CmsSlotExecutorInterface
{
    /**
     * @var \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotPluginInterface
     */
    protected $cmsSlotPlugin;

    /**
     * @var \SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface
     */
    protected $cmsSlotClient;

    /**
     * @param \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotPluginInterface $cmsSlotPlugin
     * @param \SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface $cmsSlotClient
     */
    public function __construct(
        CmsSlotPluginInterface $cmsSlotPlugin,
        ShopCmsSlotToCmsSlotClientInterface $cmsSlotClient
    ) {
        $this->cmsSlotPlugin = $cmsSlotPlugin;
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
            $autoFilledData = $this->cmsSlotClient->fetchCmsSlotAutoFilled($autoFillingKeys);
            $providedData = $autoFilledData + $providedData;
        }

        if ($requiredKeys) {
            $this->assureProvidedHasRequiredKeys($providedData, $requiredKeys);
        }

        $cmsSlotRequestTransfer = (new CmsSlotRequestTransfer())
            ->setKey($cmsSlotKey)
            ->setParams($providedData);

        return $this->cmsSlotPlugin->getSlotContent($cmsSlotRequestTransfer);
    }

    /**
     * @param array $provided
     * @param array $required
     *
     * @throws \SprykerShop\Yves\ShopCmsSlot\Exception\MissingRequiredParameterException
     *
     * @return void
     */
    protected function assureProvidedHasRequiredKeys(array $provided, array $required): void
    {
        foreach ($required as $requiredParamName) {
            if (!isset($provided[$requiredParamName])) {
                throw new MissingRequiredParameterException(
                    sprintf('The "%s" param is missing in the provided data', $requiredParamName)
                );
            }
        }
    }
}
