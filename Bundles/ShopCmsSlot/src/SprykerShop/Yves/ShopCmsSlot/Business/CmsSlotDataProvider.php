<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Generated\Shared\Transfer\CmsSlotContextTransfer;
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
     * @param \Generated\Shared\Transfer\CmsSlotContextTransfer $cmsSlotContextTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function getSlotContent(CmsSlotContextTransfer $cmsSlotContextTransfer): CmsSlotContentResponseTransfer
    {
        $providedData = $cmsSlotContextTransfer->getProvidedData();
        $autoFilledKeys = $cmsSlotContextTransfer->getAutoFilledKeys();

        $this->assureProvidedHasRequiredKeys($providedData, $cmsSlotContextTransfer->getRequiredKeys());

        if ($autoFilledKeys) {
            $autoFilledData = $this->cmsSlotClient->getCmsSlotExternalDataByKeys($autoFilledKeys)->getValues();
            $providedData = $this->mergeProvidedData($autoFilledData, $providedData);
        }

        $cmsSlotContentRequestTransfer = $this->createCmsSlotContentRequestTransfer($cmsSlotContextTransfer, $providedData);

        return $this->cmsSlotContentPlugin->getSlotContent($cmsSlotContentRequestTransfer);
    }

    /**
     * @param array $provided
     * @param string[] $requiredKeys
     *
     * @throws \SprykerShop\Yves\ShopCmsSlot\Exception\MissingRequiredParameterException
     *
     * @return void
     */
    protected function assureProvidedHasRequiredKeys(array $provided, array $requiredKeys): void
    {
        if (!$requiredKeys) {
            return;
        }

        foreach ($requiredKeys as $requiredKey) {
            if (!isset($provided[$requiredKey])) {
                throw new MissingRequiredParameterException(
                    sprintf('Unable to find provided data for the key "%s"', $requiredKey)
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContextTransfer $cmsSlotContextTransfer
     * @param \Generated\Shared\Transfer\CmsSlotExternalDataTransfer[] $providedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentRequestTransfer
     */
    protected function createCmsSlotContentRequestTransfer(
        CmsSlotContextTransfer $cmsSlotContextTransfer,
        array $providedData
    ): CmsSlotContentRequestTransfer {
        return (new CmsSlotContentRequestTransfer())
            ->setCmsSlotKey($cmsSlotContextTransfer->getCmsSlotKey())
            ->setCmsSlotTemplatePath($cmsSlotContextTransfer->getCmsSlotTemplatePath())
            ->setParams($providedData);
    }

    /**
     * @param string[] $autoFilledData
     * @param \Generated\Shared\Transfer\CmsSlotExternalDataTransfer[] $providedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotExternalDataTransfer[]
     */
    protected function mergeProvidedData(array $autoFilledData, array $providedData): array
    {
        return array_merge($autoFilledData, $providedData);
    }
}
